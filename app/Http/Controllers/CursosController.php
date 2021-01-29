<?php

namespace App\Http\Controllers;

use App\Usuario;
use Carbon\Carbon;
use App\Models\Curso;
use App\Models\Centro;
use App\Functions\Emails;
use App\Models\Instructore;
use Illuminate\Http\Request;

use App\Exports\CursosExport;
use App\Models\CatalogoCurso;
use App\Models\UsuariosCentro;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use App\Models\Bitacora as ModelsBitacora;

class CursosController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $id_centros = UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro');
        //Log::error('idcentros:'.json_encode($id_centros));
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';


        $centro_filtro='';
        //Filtramos por un centro en específico
        if($request['id_centro'] && $request['id_centro']){
            $id_centro = $request['id_centro'];
            $centro_filtro = " AND catalogo_cursos.id_centro = $id_centro";
        }


        $cursos = Curso::
            join('catalogo_cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->join('centros as centro', 'catalogo_cursos.id_centro', 'centro.id')
            ->join('catalogos_detalles as departamento', 'cursos.id_departamento', 'departamento.id')
            ->join('catalogos_detalles as estado', 'cursos.id_estado', 'estado.id')
            ->join('catalogos_detalles as modalidad', 'cursos.id_modalidad', 'modalidad.id')
            ->join('catalogos_detalles as modo', 'cursos.id_modo', 'modo.id')
            ->join('catalogos_detalles as municipio', 'cursos.id_municipio', 'municipio.id')
            ->join('catalogos_detalles as pais', 'cursos.id_pais', 'pais.id')
            ->join('catalogos_detalles as sector', 'catalogo_cursos.id_sector', 'sector.id')
            ->join('catalogos_detalles as tipo', 'catalogo_cursos.id_tipo', 'tipo.id')
            ->join('catalogos_detalles as unidad_duracion', 'catalogo_cursos.id_unidad_duracion', 'unidad_duracion.id')

            ->where([
                ['cursos.deleted_at', '=', null],
                
            ])
            ->whereIn('catalogo_cursos.id_centro', $id_centros)
            ->whereRaw("(catalogo_cursos.nombre like '%$request->filtro%' OR cursos.codigo like '%$request->filtro%') $centro_filtro", [])
            ->selectRaw("cursos.id,
					catalogo_cursos.id_centro,
					catalogo_cursos.id_tipo,
					catalogo_cursos.id_sector,
					cursos.id_modalidad,
					cursos.id_modo,
					catalogo_cursos.nombre,
					catalogo_cursos.descripcion,
					catalogo_cursos.competencias_adquiridas,
					cursos.id_pais,
					cursos.id_departamento,
					cursos.id_municipio,
					cursos.direccion,
					DATE_FORMAT(cursos.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
					DATE_FORMAT(cursos.fecha_fin, '%d/%m/%Y') as fecha_fin,
					DATE_FORMAT(cursos.fecha_fin_matricula, '%d/%m/%Y') as fecha_fin_matricula,
					catalogo_cursos.id_unidad_duracion,
					catalogo_cursos.duracion,
					cursos.id_estado,
					cursos.certificado,
					cursos.costo,
                    cursos.cupos,
                    cursos.codigo,
					centro.nombre as centro,
					departamento.nombre as departamento,
					estado.nombre as estado,
					modalidad.nombre as modalidad,
					modo.nombre as modo,
					municipio.nombre as municipio,
					pais.nombre as pais,
					sector.nombre as sector,
					tipo.nombre as tipo,
                    unidad_duracion.nombre as unidad_duracion", [])
                    ->orderBy($sortBy, $descending)
            ->paginate($rowsPerPage, ['*'], 'Page', $page);
    
        
        return response()->json(["cursos" => $cursos], 200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {

        //Validate inputs

        $curso = $request["curso"];
        $validator = Validator::make(
            $curso,
            [
                'id_curso' => 'required|numeric',
                'id_modalidad' => 'required|numeric',
                'id_modo' => 'required|numeric',
                'id_modalidad' => 'required|numeric',
                'cupos' => 'required|numeric',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'fecha_fin_matricula' => 'required',
                'id_estado' => 'required|numeric',
                'certificado' => 'required',
                'costo' => 'required|numeric',

            ]
        );
        $validator->validate();

        try {
            DB::beginTransaction();
            $curso = Curso::create([
                'id_curso' => $curso['id_curso'],
                'id_modalidad' => $curso['id_modalidad'],
                'id_modo' => $curso['id_modo'],
                'codigo' => $curso['codigo'],
                'id_modalidad' => $curso['id_modalidad'],
                'id_modo' => $curso['id_modo'],
                'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $curso['fecha_inicio']),
                'fecha_fin' => Carbon::createFromFormat('d/m/Y', $curso['fecha_fin']),
                'fecha_fin_matricula' => Carbon::createFromFormat('d/m/Y', $curso['fecha_fin_matricula']),
                'id_estado' => $curso['id_estado'],
                'id_pais' => $curso['id_pais'],
                'id_departamento' => $curso['id_departamento'],
                'id_municipio' => $curso['id_municipio'],
                'direccion' => $curso['direccion'],
                'costo' => $curso['costo'],
                'cupos' => $curso['cupos'],
                'certificado' => $curso['certificado']
            ]);

            $_instructores  = $request["instructores"];
            $instructores = array();
            foreach ($_instructores as $instructor) {
                if ($instructor['checked']) {
                    array_push($instructores, $instructor['id']);
                }
            }
            $curso->instructores()->attach($instructores);
            $curso->save();
            $catalogo_curso = CatalogoCurso::find($curso['id_curso']);
            $admins = Usuario::whereIn('id_rol', [1,2])->get();
            Emails::EnviarCorreo($request->user()->id, $admins, 'Nuevo Curso Pendiente de Aprobación', 'emails.aprobacion_pendiente', ['nombre_curso' => $catalogo_curso->nombre, 'id_curso'=> $curso->id]);
            $log = new BitacoraHelper();
            $log->log($request, 'Crea Curso', 'Curso', $curso->id);
            DB::commit();
            return response()->json(
                ['result' => true],
                200
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function show($id)
    {
        $curso = Curso::findOrFail($id);
        return response()->json(["curso" =>  $curso], 200);
    }
    public function edit($id)
    {
        $curso = Curso::
            join('catalogo_cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->join('centros', 'centros.id', 'catalogo_cursos.id_centro')
            ->join('catalogos_detalles as estado', 'cursos.id_estado', 'estado.id')
            ->join('catalogos_detalles as modalidad', 'cursos.id_modalidad', 'modalidad.id')
            ->join('catalogos_detalles as modo', 'cursos.id_modo', 'modo.id')
            ->join('catalogos_detalles as tipo', 'catalogo_cursos.id_tipo', 'tipo.id')
            ->join('catalogos_detalles as sector', 'catalogo_cursos.id_sector', 'sector.id')
            ->join('catalogos_detalles as pais', 'cursos.id_pais', 'pais.id')
            ->join('catalogos_detalles as departamento', 'cursos.id_departamento', 'departamento.id')
            ->join('catalogos_detalles as municipio', 'cursos.id_municipio', 'municipio.id')
            ->join('catalogos_detalles as unidad_duracion', 'catalogo_cursos.id_unidad_duracion', 'unidad_duracion.id')

            ->where([
                ['cursos.id', '=', $id]
            ])

            ->selectRaw("cursos.id,
			cursos.codigo,
					cursos.id_curso,
					catalogo_cursos.id_tipo,
					sector.nombre as sector,
					cursos.id_modalidad,
					cursos.id_modo,
					catalogo_cursos.nombre,
					catalogo_cursos.descripcion,
					catalogo_cursos.competencias_adquiridas,
					cursos.id_pais,
					cursos.id_departamento,
					cursos.id_municipio,
					cursos.direccion,
					cast(catalogo_cursos.id_centro as unsigned) id_centro ,
					DATE_FORMAT(cursos.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
					DATE_FORMAT(cursos.fecha_fin, '%d/%m/%Y') as fecha_fin,
					DATE_FORMAT(cursos.fecha_fin_matricula, '%d/%m/%Y') as fecha_fin_matricula,
					catalogo_cursos.id_unidad_duracion,
					catalogo_cursos.duracion,
					cursos.id_estado,
					cursos.certificado,
					cursos.costo,
					cursos.cupos,
					cast(cursos.cupos - (select count(1) from cursos_matriculas   where cursos_matriculas.id_curso = cursos.id and deleted_at is null) as unsigned) as cupos_disponibles,
					centros.nombre as centro,
					departamento.nombre as departamento,
					estado.nombre as estado,
					modalidad.nombre as modalidad,
					modo.nombre as modo,
					municipio.nombre as municipio,
					pais.nombre as pais,
					sector.nombre as sector,
					tipo.nombre as tipo,
					unidad_duracion.nombre as unidad_duracion", [])
            ->first();

        $_instructores = Curso::findOrFail($id)->instructores()->select("instructores.id")->get()->pluck('id')->toArray();
        $id_centro = CatalogoCurso::where('id', $curso->id_curso)->value('id_centro');
        $instructores = Instructore::
        join('usuarios', 'usuarios.id', 'instructores.id_usuario')
        ->join('usuarios_centros', 'usuarios.id', 'usuarios_centros.id_usuario')
        ->selectRaw("instructores.id, concat(nombres,' ',apellidos) as nombre,false checked", [])
        ->whereIn('usuarios_centros.id_centro', [ $id_centro])
        ->orderBy("nombres")
            ->get();
        foreach ($instructores as $key => $value) {
            if (in_array($value->id, $_instructores)) {
                $instructores[$key]->checked = true;
            }
        }

        return response()->json(['curso' => $curso, 'instructores' => $instructores], 200);
    }
    public function update(Request $request)
    {

        //Validate inputs

        $curso = $request["curso"];
        $validator = Validator::make(
            $curso,
            [
                'id_curso' => 'required|numeric',
                'id_modalidad' => 'required|numeric',
                'id_modo' => 'required|numeric',
                'id_pais' => 'required|numeric',
                'id_departamento' => 'required|numeric',
                'id_municipio' => 'required|numeric',
                'direccion' => 'required|max:191',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required',
                'fecha_fin_matricula' => 'required',
                'id_estado' => 'required|numeric',
                'certificado' => 'required',
                'costo' => 'required|numeric',
                'cupos' => 'required|numeric'

            ]
        );
        $validator->validate();
        $update = $request["curso"];
        $curso = Curso::findOrFail($update["id"]);

        try {
            DB::beginTransaction();
            $curso->id = $update['id'];
            $curso->id_modalidad = $update['id_modalidad'];
            $curso->id_modo = $update['id_modo'];
            $curso->id_pais = $update['id_pais'];
            $curso->id_departamento = $update['id_departamento'];
            $curso->id_municipio = $update['id_municipio'];
            $curso->direccion = $update['direccion'];
            $curso->fecha_inicio = Carbon::createFromFormat('d/m/Y', $update['fecha_inicio']);
            $curso->fecha_fin = Carbon::createFromFormat('d/m/Y', $update['fecha_fin']);
            $curso->fecha_fin_matricula = Carbon::createFromFormat('d/m/Y', $update['fecha_fin_matricula']);
            $curso->id_estado = $update['id_estado'];
            $curso->certificado = $update['certificado'];
            $curso->costo = $update['costo'];
            $curso->cupos = $update['cupos'];

            $_instructores  = $request["instructores"];
            $instructores = array();
            foreach ($_instructores as $instructor) {
                if ($instructor['checked']) {
                    array_push($instructores, $instructor['id']);
                }
            }
            $curso->instructores()->detach();
            $curso->instructores()->attach($instructores);
            $curso->save();

            $log = new BitacoraHelper();
            $log->log($request, 'Actualiza Curso', 'Curso', $curso->id);

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
    public function destroy(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        $log = new BitacoraHelper();
        $log->log($request, 'Elimina Curso', 'Curso', $curso->id);
        return response()->json([], 204);
    }

    public function cambiarEstado(Request $request)
    {
        try {
            DB::beginTransaction();
            $id_estado = $request['id_estado'];
            $id = $request['id'];
            $curso = Curso::findOrFail($id);
            $curso->id_estado = $id_estado;
            $curso->save();

            //Si el curso es aprobado enviamos correo a la persona que creó el curso
            //Para informarle que el curso ha sido aprobado
            if ($curso->id_estado === 5532) {
                $usuario_creacion =ModelsBitacora::with('usuario')->where([
                    ['id_model',$curso->id],
                    ['action','Crea Curso'],
                    ['model','Curso']
                ])->first()->usuario;
                Emails::EnviarCorreoCursoAprobado($curso, $request->user(), $usuario_creacion);
            }

            $log = new BitacoraHelper();
            $log->log($request, 'Cambia estado', 'Curso', $curso->id);
            DB::commit();
            return response()->json(['result' => true], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function DescargarExcel(Request $request)
    {
        $user = $request->user();
        $filtro = $request->filtro;
        $export = new CursosExport($user, $filtro);

        $log = new BitacoraHelper();
        $log->log($request, 'Exporta cursos a excel', 'Curso', null);
        return $export->download('cursos.xlsx');
    }


    public function storeCatalogoCurso(Request $request)
    {
        //Validate inputs

        $curso = $request["curso"];
        $validator = Validator::make(
            $curso,
            [
                'id_tipo' => 'required|numeric',
                'id_sector' => 'required|numeric',
                'id_unidad_duracion' => 'required|numeric',
                'nombre' => 'required|max:500',
                'descripcion' => 'max:2000',
                'competencias_adquiridas' => 'max:2000',
                'duracion' => 'required|numeric',
    
            ]
        );
        $validator->validate();

        $user = $request->user();
        $usuario_centro = UsuariosCentro::where('id_usuario', $user->id)->value('id_centro');
        $id_centro = $user->id_rol ==1 || $user->id_rol ==2 ?   $curso['id_centro'] : $usuario_centro;

        CatalogoCurso::create([
                'id_tipo' => $curso['id_tipo'],
                'id_centro' => $id_centro,
                'id_sector' => $curso['id_sector'],
                'id_unidad_duracion' => $curso['id_unidad_duracion'],
                'nombre' => $curso['nombre'],
                'descripcion' => $curso['descripcion'],
                'competencias_adquiridas' => $curso['competencias_adquiridas'],
                'duracion' => $curso['duracion'],
                //'created_at' => catalogo_curso['created_at']),
                //'updated_at' => catalogo_curso['updated_at'])
            ]);
    
        return response()->json(
            ['result' => true ],
            200
        );
    }

    public function updateCatalogoCurso(Request $request)
    {
        
        //Validate inputs

        $curso = $request["curso"];
        $validator = Validator::make(
            $curso,
            [
            'id_tipo' => 'required|numeric',
            'id_centro' => 'required|numeric',
            'id_sector' => 'required|numeric',
            'id_unidad_duracion' => 'required|numeric',
            'nombre' => 'required|max:500',
            'descripcion' => 'max:2000',
            'competencias_adquiridas' => 'max:2000',
            'duracion' => 'required|numeric',

        ]
        );
        $validator->validate();
        $update = $request["curso"];
        $catalogo_curso = CatalogoCurso::findOrFail($update["id"]);
        
        $catalogo_curso->id = $update['id'];
        $catalogo_curso->id_tipo = $update['id_tipo'];
        $catalogo_curso->id_centro = $update['id_centro'];
        $catalogo_curso->id_sector = $update['id_sector'];
        $catalogo_curso->id_unidad_duracion = $update['id_unidad_duracion'];
        $catalogo_curso->nombre = $update['nombre'];
        $catalogo_curso->descripcion = $update['descripcion'];
        $catalogo_curso->competencias_adquiridas = $update['competencias_adquiridas'];
        $catalogo_curso->duracion = $update['duracion'];

        $catalogo_curso->save();
        return response()->json(
            ["result"=> true],
            201
        );
    }
    public function destroyCatalogoCurso($id)
    {
        try {
            $catalogo_curso = CatalogoCurso::findOrFail($id);
            $catalogo_curso->delete();
            return response()->json([], 204);
        } catch (\Throwable $th) {
            throw new \Exception('Ha ocurrido un error al eliminar el curso seleccionado');
        }
    }
    
    public function indexCatalogoCurso(Request $request)
    {
        $page =$request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage >0 ? $request->rowsPerPage : 999999999999999999;

        $usuario = $request->user();
        $id_centros = UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro');


        $centro_filtro='';
        //Filtramos por un centro en específico
        if ($request['id_centro'] ) {
            $id_centros = $request['id_centro'];
            //$centro_filtro = " AND catalogo_cursos.id_centro = $id_centros";
        }




        $catalogo_cursos =DB::table('catalogo_cursos')
        ->join('centros as centro', 'catalogo_cursos.id_centro', 'centro.id')
        ->join('catalogos_detalles as sector', 'catalogo_cursos.id_sector', 'sector.id')
        ->join('catalogos_detalles as tipo', 'catalogo_cursos.id_tipo', 'tipo.id')
        ->join('catalogos_detalles as unidad_duracion', 'catalogo_cursos.id_unidad_duracion', 'unidad_duracion.id')
        
       
        ->whereRaw("(catalogo_cursos.deleted_at is null AND catalogo_cursos.nombre like '%$request->filtro%') ", [])
        ->whereIn('catalogo_cursos.id_centro',$id_centros)
        ->selectRaw("catalogo_cursos.id,
					catalogo_cursos.id_tipo,
					catalogo_cursos.id_centro,
					catalogo_cursos.id_sector,
					catalogo_cursos.id_unidad_duracion,
					catalogo_cursos.nombre,
					catalogo_cursos.descripcion,
					catalogo_cursos.competencias_adquiridas,
					catalogo_cursos.duracion,
					centro.nombre as centro,
					sector.nombre as sector,
					tipo.nombre as tipo,
                    unidad_duracion.nombre as unidad_duracion", [])
                    ->orderBy('id','desc')
                  ->paginate($rowsPerPage, ['*'], 'Page', $page);
      
       
        return response()->json(["catalogo_cursos"=> $catalogo_cursos], 200);
    }
    

    public function editCatalogoCurso($id)
    {
        $catalogo_curso =CatalogoCurso::
        join('centros as centro', 'catalogo_cursos.id_centro', 'centro.id')
        ->join('catalogos_detalles as sector', 'catalogo_cursos.id_sector', 'sector.id')
        ->join('catalogos_detalles as tipo', 'catalogo_cursos.id_tipo', 'tipo.id')
        ->join('catalogos_detalles as unidad_duracion', 'catalogo_cursos.id_unidad_duracion', 'unidad_duracion.id')
        ->whereRaw("catalogo_cursos.id = $id", [])
        ->selectRaw("catalogo_cursos.id,
					catalogo_cursos.id_tipo,
					catalogo_cursos.id_centro,
					catalogo_cursos.id_sector,
					catalogo_cursos.id_unidad_duracion,
					catalogo_cursos.nombre,
					catalogo_cursos.descripcion,
					catalogo_cursos.competencias_adquiridas,
					catalogo_cursos.duracion,
					centro.nombre as centro,
					sector.nombre as sector,
					tipo.nombre as tipo,
					unidad_duracion.nombre as unidad_duracion", [])
        ->first();
      
        return response()->json(["catalogo_curso"=> $catalogo_curso], 200);
    }

    public function getCodigo(Request $request)
    {
        $id_catalogo_curso = (int) $request['id_curso'];
        $catalogo_curso = CatalogoCurso::with('centro')->findOrFail($id_catalogo_curso);
        $nombre_centro = $this->acronym($catalogo_curso->centro->nombre);
        $consecutivo = Curso::whereRaw("id_curso= $id_catalogo_curso AND year(fecha_inicio) = year(now())",[])->count() +1;
        $nombre_curso = $this->acronym($catalogo_curso->nombre);

        $centro = Centro::find($catalogo_curso->id_centro);
        $anio =date('Y');
        $codigo = strtoupper($nombre_centro.'-'.$nombre_curso."-".$anio.'-'.$consecutivo);
        $id_departamento = $centro->id_departamento;
        $id_municipio = $centro->id_municipio;
        $direccion = $centro->direccion;

        return response()->json(['codigo'=> $codigo,'id_departamento'=> $id_departamento,'id_municipio'=> $id_municipio,'direccion' => $direccion]);
    }



    public function acronym($string)
    {
        $string = str_replace("á", "a", $string);
        $string = str_replace("é", "e", $string);
        $string = str_replace("í", "i", $string);
        $string = str_replace("ó", "o", $string);
        $string = str_replace("ú", "u", $string);
        $words = explode(" ", $string);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }
        return strtoupper($acronym);
    }   

    public function getDatos(Request $request){

        $_curso = Curso::where('id',$request['id_curso'])->first();
        $curso = CatalogoCurso::where('id',$_curso->id_curso)->first();
        return Response()->json(['result' => true, 'data' =>$curso,],200);

    }
}
