<?php
namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Functions\BitacoraHelper;
use App\Exports\ParticipantesExport;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ParticipantesController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        $page =$request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage >0 ? $request->rowsPerPage : 999999999999999999;

        $participantes =Participante::withTrashed()
        ->join('catalogos_detalles as ciudad', 'participantes.id_ciudad', 'ciudad.id')
        ->join('catalogos_detalles as departamento', 'participantes.id_departamento', 'departamento.id')
        ->join('catalogos_detalles as estado_civil', 'participantes.id_estado_civil', 'estado_civil.id')
        ->join('catalogos_detalles as nivel_educacion', 'participantes.id_nivel_educacion', 'nivel_educacion.id')
        ->join('catalogos_detalles as pais', 'participantes.id_pais', 'pais.id')
        ->join('catalogos_detalles as tipo_identificacion', 'participantes.id_tipo_identificacion', 'tipo_identificacion.id')
        
        ->whereRaw(" concat(participantes.nombres,' ', participantes.apellidos ) like '%$request->filtro%' 
		AND exists (select 1 
		from cursos_matriculas as cm 
		inner join cursos as c on cm.id_curso = c.id
		inner join catalogo_cursos as cc on c.id_curso = cc.id
		where cm.id_participante = participantes.id and cc.id_centro in ($id_centros) ) ", [])
        ->selectRaw("participantes.id,
					participantes.foto,
					participantes.nombres,
					participantes.apellidos,
					concat(participantes.nombres,' ', participantes.apellidos ) as nombre_completo,
					participantes.telefono,
					participantes.correo,
					participantes.id_tipo_identificacion,
					participantes.documento_identidad,
					participantes.menor_edad,
					DATE_FORMAT(participantes.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
					TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) AS edad,
					participantes.id_estado_civil,
					participantes.sexo,
					participantes.id_pais,
					participantes.id_departamento,
					participantes.id_ciudad,
					participantes.direccion,
					participantes.id_nivel_educacion,
					participantes.estudiando,
					participantes.curso_estudiando,
					participantes.trabajando,
					participantes.lugar_trabajo,
					participantes.salario,
					participantes.referencia_nombre,
					participantes.id_parentezco,
					participantes.referencia_cedula,
					participantes.referencia_telefono,
					participantes.referencia_correo,
					ciudad.nombre as ciudad,
					departamento.nombre as departamento,
					estado_civil.nombre as estado_civil,
					nivel_educacion.nombre as nivel_educacion,
					pais.nombre as pais,
					tipo_identificacion.nombre as tipo_identificacion,
					case when participantes.deleted_at is null then  'Activo' else 'Inactivo' end as estado", [])
        ->paginate($rowsPerPage, ['*'], 'Page', $page);
        return response()->json(["participantes"=> $participantes], 200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //No  está permitido crear participantes desde este controlador, deben de ser creados en las matriculas para los cursos
      
        //Validate inputs

        $participante = $request["participante"];
        $validator = Validator::make(
            $participante,
            [
            'foto' => 'max:250',
            'nombres' => 'required|max:50',
            'apellidos' => 'required|max:50',
            'telefono' => 'max:20',
            'correo' => 'max:128',
            'documento_identidad' => 'max:30',
            'menor_edad' => 'required',
            'fecha_nacimiento' => 'required',
            'id_estado_civil' => 'required|numeric',
            'sexo' => 'required|max:191',
            'id_pais' => 'required|numeric',
            'id_departamento' => 'required|numeric',
            'id_ciudad' => 'required|numeric',
            'direccion' => 'required|max:255',
            'id_nivel_educacion' => 'required|numeric',
            'estudiando' => 'required',
            'curso_estudiando' => 'max:191',
            'trabajando' => 'required',
            'lugar_trabajo' => 'max:191',
            'salario' => 'required|numeric',
            'referencia_nombre' => 'max:191',
            'id_parentezco' => 'numeric',
            'referencia_cedula' => 'max:191',
            'referencia_telefono' => 'max:191',
            'referencia_correo' => 'max:191',

        ]
        );
        $validator->validate();

        $participante['documento_identidad']   = str_replace('-', '', $participante['documento_identidad']);
        $participante['documento_identidad']   = str_replace('.', '', $participante['documento_identidad']);

        $item = Participante::where('documento_identidad', $participante['documento_identidad'])->first();
        if ($item) {
            return response()->json(['result' => false,
            'message' => "La cedula '$item->documento_identidad' ya existe en la base de datos"
        ], 422);
        }

      
        $participante =Participante::create([
            //'id' => participante['id']),
            'nombres' => $participante['nombres'],
            'apellidos' => $participante['apellidos'],
            'telefono' => $participante['telefono'],
            'correo' => $participante['correo'],
            'id_tipo_identificacion' => 5496,
            'documento_identidad' => $participante['documento_identidad'],
            'menor_edad' => $participante['menor_edad'],
            'fecha_nacimiento' => Carbon::createFromFormat('d/m/Y', $participante['fecha_nacimiento']),
            'id_estado_civil' => $participante['id_estado_civil'],
            'sexo' => $participante['sexo'],
            'id_pais' => $participante['id_pais'],
            'id_departamento' => $participante['id_departamento'],
            'id_ciudad' => $participante['id_ciudad'],
            'direccion' => $participante['direccion'],
            'id_nivel_educacion' => $participante['id_nivel_educacion'],
            'estudiando' => $participante['estudiando'],
            'curso_estudiando' => $participante['curso_estudiando'],
            'trabajando' => $participante['trabajando'],
            'lugar_trabajo' => $participante['lugar_trabajo'],
            'salario' => $participante['salario'],
            'referencia_nombre' =>  $participante['referencia_nombre'] ,
            'id_parentezco' => $participante['id_parentezco'],
            'referencia_cedula' => $participante['referencia_cedula'],
            'referencia_telefono' => $participante['referencia_telefono'],
            'referencia_correo' => $participante['referencia_correo'],
        
        ]);

        $log = new BitacoraHelper();
        $log->log($request, 'Crea Participante', 'Participantes', $participante->id);

        return response()->json(['result' => true, 'participante' => $participante ], 200);
    }
    public function show($id)
    {
        $participante = Participante::findOrFail($id);
        return response()->json([ "participante" =>  $participante], 200);
    }
    public function edit($id)
    {
        $participante =Participante::
        join('catalogos_detalles as ciudad', 'participantes.id_ciudad', 'ciudad.id')
        ->join('catalogos_detalles as departamento', 'participantes.id_departamento', 'departamento.id')
        ->join('catalogos_detalles as estado_civil', 'participantes.id_estado_civil', 'estado_civil.id')
        ->join('catalogos_detalles as nivel_educacion', 'participantes.id_nivel_educacion', 'nivel_educacion.id')
        ->join('catalogos_detalles as pais', 'participantes.id_pais', 'pais.id')
        ->join('catalogos_detalles as tipo_identificacion', 'participantes.id_tipo_identificacion', 'tipo_identificacion.id')
        
        ->where('participantes.id', $id)
        ->selectRaw("participantes.id,
					participantes.foto,
					participantes.nombres,
					participantes.apellidos,
					participantes.telefono,
					participantes.correo,
					participantes.id_tipo_identificacion,
					participantes.documento_identidad,
					participantes.menor_edad,
					DATE_FORMAT(participantes.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
					participantes.id_estado_civil,
					participantes.sexo,
					participantes.id_pais,
					participantes.id_departamento,
					participantes.id_ciudad,
					participantes.direccion,
					participantes.id_nivel_educacion,
					participantes.estudiando,
					participantes.curso_estudiando,
					participantes.trabajando,
					participantes.lugar_trabajo,
					participantes.salario,
					participantes.referencia_nombre,
					participantes.id_parentezco,
					participantes.referencia_cedula,
					participantes.referencia_telefono,
					participantes.referencia_correo,
					ciudad.nombre as ciudad,
					departamento.nombre as departamento,
					estado_civil.nombre as estado_civil,
					nivel_educacion.nombre as nivel_educacion,
					pais.nombre as pais,
					tipo_identificacion.nombre as tipo_identificacion", [])
        ->first();
        return response()->json(["participante"=> $participante], 200);
    }
    public function update(Request $request)
    {

        //Validate inputs

        $participante = $request["participante"];
        $validator = Validator::make(
            $participante,
            [
            'nombres' => 'required|max:50',
            'apellidos' => 'required|max:50',
            'telefono' => 'max:20',
            'correo' => 'max:128',
            'documento_identidad' => 'max:30',
            'menor_edad' => 'required',
            'fecha_nacimiento' => 'required',
            'id_estado_civil' => 'required|numeric',
            'sexo' => 'required|max:191',
            'id_pais' => 'required|numeric',
            'id_departamento' => 'required|numeric',
            'id_ciudad' => 'required|numeric',
            'direccion' => 'required|max:255',
            'id_nivel_educacion' => 'required|numeric',
            'estudiando' => 'required',
            'curso_estudiando' => 'max:191',
            'trabajando' => 'required',
            'lugar_trabajo' => 'max:191',
            'salario' => 'required|numeric',
            'referencia_nombre' => 'max:191',
            'id_parentezco' => 'numeric',
            'referencia_cedula' => 'max:191',
            'referencia_telefono' => 'max:191',
            'referencia_correo' => 'max:191',

        ]
        );
        $validator->validate();
        $update = $request["participante"];
        $participante = Participante::findOrFail($update["id"]);
        
        $participante->id = $update['id'];
        // $participante->foto = $update['foto'];
        $participante->nombres = $update['nombres'];
        $participante->apellidos = $update['apellidos'];
        $participante->telefono = $update['telefono'];
        $participante->correo = $update['correo'];
        // $participante->id_tipo_identificacion = $update['id_tipo_identificacion'];
        $participante->documento_identidad = $update['documento_identidad'];
        $participante->menor_edad = $update['menor_edad'];
        $participante->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $update['fecha_nacimiento']);
        $participante->id_estado_civil = $update['id_estado_civil'];
        $participante->sexo = $update['sexo'];
        $participante->id_pais = $update['id_pais'];
        $participante->id_departamento = $update['id_departamento'];
        $participante->id_ciudad = $update['id_ciudad'];
        $participante->direccion = $update['direccion'];
        $participante->id_nivel_educacion = $update['id_nivel_educacion'];
        $participante->estudiando = $update['estudiando'];
        $participante->curso_estudiando = $update['curso_estudiando'];
        $participante->trabajando = $update['trabajando'];
        $participante->lugar_trabajo = $update['lugar_trabajo'];
        $participante->salario = $update['salario'];
        //solo actualizamos la informacion en caso que sea menor de edad sino ponemos null
        $participante->referencia_nombre = $update['referencia_nombre'];
        $participante->id_parentezco =  $update['id_parentezco'] ;
        $participante->referencia_cedula =  $update['referencia_cedula'] ;
        $participante->referencia_telefono =  $update['referencia_telefono'];
        $participante->referencia_correo =  $update['referencia_correo'];

        $participante->save();
        
        $log = new BitacoraHelper();
        $log->log($request, 'Actualiza Participante', 'Participantes', $participante->id);
        return response()->json(["result"=> true,'participante'=>$participante], 201);
    }
    public function destroy(Request $request, $id)
    {
        $participante = Participante::withTrashed()->findOrFail($id);
        //Si el participante ya esta eliminado lo activamos
        $eliminar = $participante->deleted_at ===null;
        if ($participante->deleted_at ===null) {
            $participante->delete();
        } else {
            $participante->restore();
        }
        
        $log = new BitacoraHelper();
        $log->log($request, $eliminar? 'Inactivar ':'Reactivar'.' Participante', 'Participantes', $participante->id);
        return response()->json([], 204);
    }
    
    public function uploadimage(Request $request)
    {
        $_participante = json_decode($request->participante);
        $participante = participante::findOrFail($_participante->id);
        $url_foto = "/img/participantes/fotos/";
        $delete_foto = $_participante->foto ===null;


        if ($delete_foto) {
            $image_path = public_path($url_foto) . $participante->foto;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $participante->foto = null;
            $participante->save();
        }


        if ($request->hasFile('imagen')) {
            $file      = $request->file('imagen');
            $foto   = date('YmdHis') . '-' . str_pad($participante->id, 6, "0", STR_PAD_LEFT);
            $file->move(public_path($url_foto), $foto);
            $image_path = public_path($url_foto) . $participante->foto;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $participante->foto = $foto;
            $participante->save();
            $url_foto =  env('APP_URL') . $url_foto . $foto;
        }


        return response()->json(
            [
                "message" => "Image Uploaded Succesfully",
                'urlFoto' => $url_foto,
            ]
        );
    }

    public function DescargarExcel(Request $request)
    {
        $export = new ParticipantesExport();

        $log = new BitacoraHelper();
        $log->log($request, 'Exporta lista de participantes', 'Participantes', null);
        return $export->download('participantes.xlsx');
    }
}
