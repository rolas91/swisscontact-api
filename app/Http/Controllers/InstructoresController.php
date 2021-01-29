<?php

namespace App\Http\Controllers;

use App\Usuario;
use Carbon\Carbon;
use App\Models\Centro;
use App\Functions\Usuarios;
use App\Models\Instructore;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Functions\BitacoraHelper;
use App\Models\CentrosInstructore;
use Illuminate\Support\Facades\DB;
use App\Exports\InstructoresExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class InstructoresController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;

      

        $id_centros = UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro');
        $ids_usuarios = UsuariosCentro::whereIn('id_centro', $id_centros)->pluck('id_usuario');

      
        $instructores = Instructore::
            join('catalogos_detalles as departamento', 'instructores.id_departamento', 'departamento.id')
            ->join('catalogos_detalles as municipio', 'instructores.id_municipio', 'municipio.id')
            ->join('catalogos_detalles as nivel_academico', 'instructores.id_nivel_academico', 'nivel_academico.id')
            ->join('catalogos_detalles as pais', 'instructores.id_pais', 'pais.id')
            ->join('catalogos_detalles as tipo_identificacion', 'instructores.id_tipo_identificacion', 'tipo_identificacion.id')
            ->join('usuarios', 'instructores.id_usuario', 'usuarios.id')
            ->whereIn('instructores.id_usuario', $ids_usuarios)
            ->whereRaw("concat(instructores.nombres,' ',instructores.apellidos) like '%$request->filtro%'", [])
            ->selectRaw("instructores.id,
					instructores.id_usuario,
					instructores.nombres,
					instructores.apellidos,
					instructores.telefono_1,
					instructores.telefono_2,
					instructores.telefono_otro,
					instructores.id_pais,
					instructores.id_departamento,
					instructores.id_municipio,
					instructores.sexo,
					instructores.direccion,
					DATE_FORMAT(instructores.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
					instructores.id_tipo_identificacion,
					instructores.documento_identidad,
					instructores.anios_experiencia,
					instructores.ocupacion,
					instructores.especialidad,
					instructores.calificacion,
					instructores.id_nivel_academico,
					departamento.nombre as departamento,
					municipio.nombre as municipio,
					nivel_academico.nombre as nivel_academico,
					pais.nombre as pais,
					tipo_identificacion.nombre as tipo_identificacion,
					usuarios.email,
					TIMESTAMPDIFF(YEAR, instructores.fecha_nacimiento, CURDATE()) AS edad,
					case when instructores.deleted_at is null then 'Activo' else 'Inactivo' end  as estado,
					(SELECT group_concat(c.nombre SEPARATOR ', ' )  as centro
					from usuarios_centros as uc 
					inner join centros as c on uc.id_centro = c.id
					inner join usuarios as u on uc.id_usuario =u.id
					where uc.id_usuario = instructores.id_usuario) as centros_asignados
					
                    ", [])
        ->paginate($rowsPerPage, ['*'], 'Page', $page);
    
    
        return response()->json(["instructores" => $instructores], 200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {

        //Validate inputs

        $instructore = $request["instructore"];
        $validator = Validator::make(
            $instructore,
            [
                'id_usuario' => 'required|numeric',
                'nombres' => 'required|max:50',
                'apellidos' => 'required|max:50',
                'telefono_1' => 'max:20',
                'telefono_2' => 'max:20',
                'telefono_otro' => 'max:20',
                'id_pais' => 'required|numeric',
                'id_departamento' => 'required|numeric',
                'id_municipio' => 'required|numeric',
                'sexo' => 'required|max:191',
                'direccion' => 'required|max:500',
                'fecha_nacimiento' => 'required',
                'documento_identidad' => 'required|max:30',
                'anios_experiencia' => 'required|numeric',
                'ocupacion' => 'required|max:250',
                'especialidad' => 'max:191',
                'calificacion' => 'required|numeric',
                'id_nivel_academico' => 'required|numeric',

            ]
        );
        $validator->validate();

        try {
            $instructor = Instructore::where('documento_identidad', $instructore['documento_identidad'])->first();
            if ($instructor) {
                return response()->json(['result' => false,
                'message' => "La cedula '$instructor->documento_identidad' ya existe en la base de datos"
            ], 422);
            }

            DB::beginTransaction();


            $usuario = new \stdClass();
            ;
            if (!$instructore['nuevo_usuario']) {
                $usuario =  Usuario::find($instructore['id_usuario']);
            } else {
                $usuario->nombre = $instructore['nombres'] . ' ' . $instructore['apellidos'];
                $usuario->id_rol = 5; // id_rol instructor
                $usuario->email = $instructore['correo'];
                $usuario = Usuarios::CrearUsuarioinstructor($request, $usuario);
            }

        

            $instructor = Instructore::create([
                //'id' => instructore['id']),
                'id_usuario' => $usuario->id,
                'nombres' => $instructore['nombres'],
                'apellidos' => $instructore['apellidos'],
                'telefono_1' => $instructore['telefono_1'],
                'telefono_2' => $instructore['telefono_2'],
                'telefono_otro' => $instructore['telefono_otro'],
                'id_pais' => $instructore['id_pais'],
                'id_departamento' => $instructore['id_departamento'],
                'id_municipio' => $instructore['id_municipio'],
                'sexo' => $instructore['sexo'],
                'direccion' => $instructore['direccion'],
                'fecha_nacimiento' => Carbon::createFromFormat('d/m/Y', $instructore['fecha_nacimiento']),
                'id_tipo_identificacion' => 5496, //Cedula
                'documento_identidad' => $instructore['documento_identidad'],
                'anios_experiencia' => $instructore['anios_experiencia'],
                'ocupacion' => $instructore['ocupacion'],
                'especialidad' => $instructore['especialidad'],
                'calificacion' => $instructore['calificacion'],
                'id_nivel_academico' => $instructore['id_nivel_academico']
            ]);

           
            $_centros  = $request["centros"];
            $user = $request->user();
            $centros=array();
            
            if (($user->id_rol ===1 || $user->id_rol ===2) &&  isset($_centros)) {
                foreach ($_centros as $centro) {
                    if ($centro['checked']) {
                        array_push($centros, $centro['id']);
                    }
                }
            
                $user->centros()->attach($centros);
            } else {
                //si el usuario es de tipo centro
                if (!($user->id_rol ==1 OR $user->id_rol ==2 )) {
                    //agarramos el primero, ya que si por error se le da acceso a mas de un centro se debe de seleccionar unicamente 1
                    $id_centro = UsuariosCentro::where('id_usuario', $user->id)->first()->id_centro;
                    array_push($centros, $id_centro);
                    $user->centros()->attach($centros);
                    $user->save();

                  
                }
            }
            
            $instructor->save();

            $log = new BitacoraHelper();
            $log->log($request, 'Crea Instructor', 'Instructores', $instructor->id);

            DB::commit();
            return response()->json(['result' => true], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function show($id)
    {
        $instructore = Instructore::findOrFail($id);
        return response()->json(["instructore" =>  $instructore], 200);
    }
    public function edit($id)
    {
        $instructore = instructore::join('catalogos_detalles as departamento', 'instructores.id_departamento', 'departamento.id')
                ->join('catalogos_detalles as municipio', 'instructores.id_municipio', 'municipio.id')
                ->join('catalogos_detalles as nivel_academico', 'instructores.id_nivel_academico', 'nivel_academico.id')
                ->join('catalogos_detalles as pais', 'instructores.id_pais', 'pais.id')
                ->join('catalogos_detalles as tipo_identificacion', 'instructores.id_tipo_identificacion', 'tipo_identificacion.id')
                ->join('usuarios', 'instructores.id_usuario', 'usuarios.id')
                ->withTrashed()
                ->where([
                    ['instructores.id', '=', $id]
                ])
                ->selectRaw('
          instructores.id,
        			  instructores.id_usuario,
        			  instructores.nombres,
        			  instructores.apellidos,
        			  instructores.telefono_1,
        			  instructores.telefono_2,
        			  instructores.telefono_otro,
        			  instructores.id_pais,
        			  instructores.id_departamento,
        			  instructores.id_municipio,
        			  instructores.sexo,
        			  instructores.direccion,
        			  DATE_FORMAT(instructores.fecha_nacimiento, "%d/%m/%Y") as fecha_nacimiento,
        			  instructores.id_tipo_identificacion,
        			  instructores.documento_identidad,
        			  instructores.ocupacion,
        			  instructores.especialidad,
        			  instructores.anios_experiencia,
        			  instructores.calificacion,
        			  instructores.id_nivel_academico,
        			  departamento.nombre as departamento,
        			  municipio.nombre as municipio,
        			  nivel_academico.nombre as nivel_academico,
        			  pais.nombre as pais,
        			  tipo_identificacion.nombre as tipo_identificacion,
        			  usuarios.email
                 
      
      
          ')->first();

        $instructor = Instructore::find($id);
        $id_usuario = $instructor->id_usuario;
        $_centros = Usuario::find($id_usuario)->centros()->select('centros.id', 'centros.nombre')->get()->pluck('id')->toArray();
        $centros =Centro::
                selectRaw("id, nombre,false checked", [])
                ->orderBy("nombre")
                ->get();
                
        foreach ($centros as $key => $value) {
            if (in_array($value->id, $_centros)) {
                $centros[$key]->checked = true;
            } else {
                $centros[$key]->checked =false;
            }
        }


        return response()->json(["instructore" =>  $instructore, 'centros'=> $centros], 200);
    }
    public function update(Request $request)
    {

        //Validate inputs

        $update = $request["instructore"];
        $validator = Validator::make(
            $update,
            [
                'id_usuario' => 'required|numeric',
                'nombres' => 'required|max:50',
                'apellidos' => 'required|max:50',
                'telefono_1' => 'max:20',
                'telefono_2' => 'max:20',
                'telefono_otro' => 'max:20',
                'id_pais' => 'required|numeric',
                'id_departamento' => 'required|numeric',
                'id_municipio' => 'required|numeric',
                'sexo' => 'required|max:191',
                'direccion' => 'required|max:500',
                'fecha_nacimiento' => 'required',
                'documento_identidad' => 'required|max:30',
                'anios_experiencia' => 'required|numeric',
                'ocupacion' => 'required|max:250',
                'especialidad' => 'max:191',
                'calificacion' => 'required|numeric',
                'id_nivel_academico' => 'required|numeric',

            ]
        );
        $validator->validate();

        if($request['activar']){

            DB::beginTransaction();
            try{
                $instructor = Instructore::withTrashed()->findOrFail($update["id"]);
                $instructor->restore();
                $usuario = Usuario::withTrashed()->find($instructor->id_usuario);
                $usuario->restore();
                DB::commit();

                return response()->json(
                    ["result" => true],
                    201
                );
            }catch(\Exception $ex){
                DB::rollback();
                throw $ex;
            }
        }


        $update = $request["instructore"];
        $instructore = Instructore::findOrFail($update["id"]);


        $documento_identidad = Instructore::where([
            ['documento_identidad',$instructore['documento_identidad']],
            ['id', '<>',$update["id"]]
            ])->first();
            
        if ($documento_identidad) {
            return response()->json(['result' => false,
            'message' => 'La cedula ingresada ya existe en la base de datos'
        ], 422);
        }


        try {
            DB::beginTransaction();
            $instructore->id = $update['id'];
            $instructore->id_usuario = $update['id_usuario'];
            $instructore->nombres = $update['nombres'];
            $instructore->apellidos = $update['apellidos'];
            $instructore->telefono_1 = $update['telefono_1'];
            $instructore->telefono_2 = $update['telefono_2'];
            $instructore->telefono_otro = $update['telefono_otro'];
            $instructore->id_pais = $update['id_pais'];
            $instructore->id_departamento = $update['id_departamento'];
            $instructore->id_municipio = $update['id_municipio'];
            $instructore->sexo = $update['sexo'];
            $instructore->direccion = $update['direccion'];
            $instructore->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $update['fecha_nacimiento']);
            //$instructore->id_tipo_identificacion = 5496; //Cedula
            $instructore->documento_identidad = $update['documento_identidad'];
            $instructore->anios_experiencia = $update['anios_experiencia'];
            $instructore->ocupacion = $update['ocupacion'];
            $instructore->especialidad = $update['especialidad'];
            $instructore->calificacion = $update['calificacion'];
            $instructore->id_nivel_academico = $update['id_nivel_academico'];
            $instructore->save();
            $centros = array();
            $_centros  = $request["centros"];
            foreach ($_centros as $centro) {
                if ($centro['checked']) {
                    array_push($centros, $centro['id']);
                }
            }

            $usuario = $instructore->usuario()->first();
            $usuario->centros()->detach();
            $usuario->centros()->attach($centros);
            $instructore->save();

            $log = new BitacoraHelper();
            $log->log($request, 'Actualiza Instructor', 'Instructores', $instructore->id);



            DB::commit();
            return response()->json(
                ["result" => true],
                201
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $instructore = Instructore::findOrFail($id);
            $usuario = Usuario::find($instructore->id_usuario);
            $instructore->delete();
            $usuario->delete();
            // $log = new BitacoraHelper();
            // $log->log(request(), 'Elimina Instructor', 'Instructores', $instructore->id);
            DB::commit();
            return response()->json([], 204);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function DescargarExcel(Request $request)
    {
        $user =$request->user();
        $filtro =$request->filtro;
        $export = new InstructoresExport($user, $filtro);

        $log = new BitacoraHelper();
        $log->log($request, 'Exporta lista de instructores', 'Instructores', null);
        return $export->download('instructores.xlsx');
    }
}
