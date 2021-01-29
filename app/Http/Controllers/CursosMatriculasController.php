<?php
namespace App\Http\Controllers;

use App\Usuario;
use Carbon\Carbon;
use App\Models\Curso;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\CatalogoCurso;
use App\Models\UsuariosCentro;
use App\Models\CursosMatricula;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use App\Functions\CursosMatriculasDAL;
use App\Exports\CursosMatriculasExport;
use Illuminate\Support\Facades\Validator;

class CursosMatriculasController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        $filtro_curso=  "";
        if ((!$request->has('mobile') || $request['mobile']==='false') && $request->has('id_curso')) {
            $filtro_curso =  " AND cursos_matriculas.id_curso =". $request['id_curso'];
        }

        $page =$request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage >0 ? $request->rowsPerPage : 999999999999999999;

        $dal = new CursosMatriculasDAL($id_centros,$request->filtro,$filtro_curso);

        $cursos_matriculas = $dal->getAllInscripciones();

        $cursos_matriculas = $cursos_matriculas->paginate($rowsPerPage, ['*'], 'Page', $page);

            
        return response()->json(["cursos_matriculas"=> $cursos_matriculas], 200);
    }
    public function create()
    {
        //
    }


    public function toggleEgresado(Request $request,$id_matricula)
    {


        $calificacion = $request['calificacion'];
        $comentarios = $request['comentarios'];
        $matricula = CursosMatricula::findOrFail($id_matricula);
        $matricula->egresado = !$matricula->egresado;
        $matricula->calificacion = $calificacion ? $calificacion : 0;
        $matricula->comentarios = $comentarios;
        $matricula->save();
        return response()->json(['result' => true],200);
    }



    public function store(Request $request)
    {
        //Validate inputs
        $cursos_matricula = $request["cursos_matricula"];
        $validator = Validator::make(
            $cursos_matricula,
            [
            'id_curso' => 'required|numeric',
            'nombres_participante' => 'required|max:50',
            'apellidos_participante' => 'required|max:50',
            'telefono' => 'max:20',
            'correo' => 'max:128',
            'documento_identidad' => 'max:30',
            'fecha_nacimiento' => 'required',
            'id_estado_civil' => 'required|numeric',
            'sexo' => 'required|max:191',
            'id_pais' => 'required|numeric',
            'id_departamento' => 'required|numeric',
            'id_municipio' => 'required|numeric',
            'direccion' => 'required|max:255',
            'id_nivel_academico' => 'required|numeric',
            'estudiando' => 'required',
            'curso_estudiando' => 'max:191',
            'trabajando' => 'required',
            'lugar_trabajo' => 'max:191',
            'salario' => 'required|numeric',
            'referencia_nombre' => 'max:191',
            'id_parentesco' => 'numeric',
            'referencia_cedula' => 'max:191',
            'referencia_telefono' => 'max:191',
            'referencia_correo' => 'max:191',
            'fecha_nacimiento' => 'required'

        ]
        );
        $validator->validate();
        $curso = Curso::findOrFail($cursos_matricula['id_curso']);

        if ($curso->id_estado !== 5532) {
            return response()->json([ 'result'=> false, 'message' => 'El curso actualmente no está aceptando matriculas'], 422);
        }


        //Validamos que las fecha este en el rango de fecha de matricula
        if (Carbon::parse(Carbon::now()->toDateString())->gt(Carbon::parse($curso->fecha_fin_matricula))) {
            return response()->json([ 'result'=> false, 'message' => 'La fecha de matricula ya ha pasado, no se puede realizar esta matricula'], 422);
        }

        if ($cursos_matricula['menor_edad']) {
            //validamos que vengan los datos de  id_parentezco
            if (!$cursos_matricula['id_parentezco'] || !$cursos_matricula['referencia_nombre'] || !$cursos_matricula['referencia_cedula']) {
                return response(['result'=> false , 'message' => 'Debe de ingresar los datos de la referencia del participante'], 422);
            }
        } else {
            if (!$cursos_matricula['documento_identidad']==="" || $cursos_matricula['documento_identidad'] ==="") {
                return response(['result'=> false , 'message' => 'Debe de ingresar su cedula'], 422);
            }
        }

        //Valimamos que hay ingresado cedula, correo o telefono para identificarlo
        if (!$cursos_matricula['documento_identidad'] && !$cursos_matricula['correo'] && !$cursos_matricula['telefono']) {
            return response(['result'=> false , 'message' => 'Debe de ingresar una forma de identificarlo; Cédula, Correo electrónico o Teléfono'], 422);
        }

        //validamos que haya cupos disponible para el curso
        $matriculados = CursosMatricula::where('id_curso', $cursos_matricula['id_curso'])->count();
        $cantidad_matriculas = Curso::where('id', $cursos_matricula['id_curso'])->value('cupos');
        if ($matriculados ===$cantidad_matriculas) {
            return response()->json(['result'=> false, 'message'=>'Ya no hay cupos disponibles, no esta permitido realizar esta matricula '], 422);
        }

        $cerrar_curso_matriculas = $matriculados ===($cantidad_matriculas -1);

        $cursos_matricula['documento_identidad']  = str_replace('-', '', $cursos_matricula['documento_identidad']);
        $cursos_matricula['documento_identidad']  = str_replace('.', '', $cursos_matricula['documento_identidad']);


        //Validamos que el estudiante no existe en ningún curso a partir del 2021 , si existe regresamos error
        $curso = DB::table('cursos_matriculas as  cm')
        ->join('cursos as c','cm.id_curso','c.id')
        ->join('catalogo_cursos as cc', 'c.id_curso','cc.id')
        ->join('centros','cc.id_centro', 'centros.id')
        ->where('documento_identidad', $cursos_matricula['documento_identidad'])
        ->whereRaw("year(cm.created_at) >=2021",[])
        ->select('c.codigo', 'cc.nombre  as curso','centros.nombre as centro')
        ->first();

        if ($curso) {
            $codigo= $curso->codigo;
            $nombre= $curso->curso;
            $centro= $curso->centro;
            return response()->json(['result' => false, 'message' => "El estudiante no puede llevar más cursos, ya ha sido beneficiado con el siguiente curso: $codigo - $nombre , Centro: $centro"], 422);
        }


        //validamos que no se pueda matricular un estudiante varias veces
        $estudiante =null;
        //verificamos si ya existe el estudiante en el curso
        $estudiante = CursosMatricula::where([
            ['documento_identidad', $cursos_matricula['documento_identidad']],
            ['id_curso',$cursos_matricula['id_curso']]
        ])->first();

        //Si es menor de edad validamos que no exista por el nombre: Ni modo asi de hechizo queda esto..
        if ($cursos_matricula['menor_edad']) {
            $estudiante = CursosMatricula::whereRaw("concat(nombres_participante,' ',apellidos_participante) = ? ", [$cursos_matricula['nombres_participante'].' '.$cursos_matricula['apellidos_participante']])->first();
        }
        if ($estudiante) {
            return response()->json([ 'result'=> false,'message'=> 'Ya se encuentra registrado un participante con este nombre o numero de cedula'], 422);
        }

        try {
            DB::beginTransaction();

            $participante =null;
            //si es menor de edad realizamos la verificacion con el nombre si es mayor de edad con la cedula
            if ($cursos_matricula['menor_edad']) {
                $participante = Participante::whereRaw("concat(nombres,' ',apellidos) = ?", [$cursos_matricula['nombres_participante'].' '.$cursos_matricula['apellidos_participante']])->first();
            } else {
                $participante = Participante::where('documento_identidad', $cursos_matricula['documento_identidad'])->first();
            }
            if ($participante ===null) {
                $participante =Participante::create([
                    //'id' => participante['id']),
                    'nombres' => $cursos_matricula['nombres_participante'],
                    'apellidos' => $cursos_matricula['apellidos_participante'],
                    'correo'=> $cursos_matricula['correo'],
                    'telefono' => $cursos_matricula['telefono'],
                    'id_tipo_identificacion' => 5496,
                    'documento_identidad' => $cursos_matricula['documento_identidad'],
                    'menor_edad' => $cursos_matricula['menor_edad'],
                    'fecha_nacimiento' => Carbon::createFromFormat('d/m/Y', $cursos_matricula['fecha_nacimiento']),
                    'id_estado_civil' => $cursos_matricula['id_estado_civil'],
                    'sexo' => $cursos_matricula['sexo'],
                    'id_pais' => $cursos_matricula['id_pais'],
                    'id_departamento' => $cursos_matricula['id_departamento'],
                    'id_ciudad' => $cursos_matricula['id_municipio'],
                    'direccion' => $cursos_matricula['direccion'],
                    'id_nivel_educacion' => $cursos_matricula['id_nivel_academico'],
                    'estudiando' => $cursos_matricula['estudiando'],
                    'curso_estudiando' => $cursos_matricula['curso_estudiando'],
                    'trabajando' => $cursos_matricula['trabajando'],
                    'lugar_trabajo' => $cursos_matricula['lugar_trabajo'],
                    'salario' => $cursos_matricula['salario'],
                    //Si es menor de edad guardamos los datos del referencia del cursos_matricula en caso contrario lo omitimos
                    'referencia_nombre' =>   $cursos_matricula['referencia_nombre'] ,
                    'id_parentezco' =>  $cursos_matricula['id_parentezco'],
                    'referencia_cedula' =>  $cursos_matricula['referencia_cedula'],
                    'referencia_telefono' =>  $cursos_matricula['referencia_telefono'],
                    'referencia_correo' =>  $cursos_matricula['referencia_correo'],
                   
                
                ]);
            }


            $curso = Curso::find($cursos_matricula['id_curso']);
            $cat_curso = CatalogoCurso::find($curso->id_curso);

            $puedeVerTestHolland = Usuario::find($request->user()->id)->role()->first()
            ->accesos()
            ->where([
                ['id_acceso',19],
                ['ver',1]
                ])->count()>0;

            $guardarTestHolland = !($cat_curso->id_tipo == 5614 && $puedeVerTestHolland);
            


            $curso_matricula = CursosMatricula::create([
                //'id' => cursos_matricula['id']),
                'id_curso' => $cursos_matricula['id_curso'],
                'id_participante' => $participante->id,
                'nombres_participante' => $cursos_matricula['nombres_participante'],
                'apellidos_participante' => $cursos_matricula['apellidos_participante'],
                'correo' => $cursos_matricula['correo'],
                'telefono' => $cursos_matricula['telefono'],
                'id_tipo_identificacion' => 5496,// cedula por defecto,
                'documento_identidad' =>  $cursos_matricula['documento_identidad'],
                'edad' => Carbon::createFromFormat('d/m/Y', $cursos_matricula['fecha_nacimiento'])->diff(Carbon::now())->y,
                'id_estado_civil' => $cursos_matricula['id_estado_civil'],
                'sexo' => $cursos_matricula['sexo'],
                'id_pais' => $cursos_matricula['id_pais'],
                'id_departamento' => $cursos_matricula['id_departamento'],
                'id_municipio' => $cursos_matricula['id_municipio'],
                'direccion' => $cursos_matricula['direccion'],
                'id_nivel_academico' => $cursos_matricula['id_nivel_academico'],
                'estudiando' => $cursos_matricula['estudiando'],
                'curso_estudiando' => $cursos_matricula['curso_estudiando'],
                'trabajando' => $cursos_matricula['trabajando'],
                'lugar_trabajo' => $cursos_matricula['lugar_trabajo'],
                'salario' => $cursos_matricula['salario'],
                'referencia_nombre' => $cursos_matricula['referencia_nombre'],
                'id_parentezco' => $cursos_matricula['id_parentezco'],
                'referencia_cedula' => $cursos_matricula['referencia_cedula'],
                'referencia_telefono' => $cursos_matricula['referencia_telefono'],
                'referencia_correo' => $cursos_matricula['referencia_correo'],
                'calificacion' => 0,
                'fecha_nacmiento' =>  $cursos_matricula['fecha_nacimiento'],
                'id_test_holland' => $guardarTestHolland ? null :  $cursos_matricula['id_test_holland'],
                
            ]);

        
            //cerramos el curso y lo pasamos a estado activo
            if ($cerrar_curso_matriculas) {
                $curso->id_estado = 5533;
                $curso->save();
            }
    
            //TODO: Mandar correo notificando al administrador swisscontact que el curso ha terminado su proceso de matricula y ya esta lleno(incluir lista de matriculados)
            $log = new BitacoraHelper();
            $log->log($request, 'Crea Matricula', 'Cursos Matriculas', $curso_matricula->id);
            DB::commit();
            return response()->json(
                ['result' => true ],
                200
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function show($id)
    {
        $cursos_matricula = CursosMatricula::findOrFail($id);
        return response()->json([ "cursos_matricula" =>  $cursos_matricula], 200);
    }
    public function edit($id)
    {
        $matricula =CursosMatricula::
        join('cursos as curso', 'cursos_matriculas.id_curso', 'curso.id')
        ->join('catalogo_cursos', 'curso.id_curso', 'catalogo_cursos.id')
        ->join('catalogos_detalles as departamento', 'cursos_matriculas.id_departamento', 'departamento.id')
        ->join('catalogos_detalles as estado_civil', 'cursos_matriculas.id_estado_civil', 'estado_civil.id')
        ->join('catalogos_detalles as municipio', 'cursos_matriculas.id_municipio', 'municipio.id')
        ->join('catalogos_detalles as nivel_academico', 'cursos_matriculas.id_nivel_academico', 'nivel_academico.id')
        ->join('catalogos_detalles as pais', 'cursos_matriculas.id_pais', 'pais.id')
        ->join('catalogos_detalles as parentezco', 'cursos_matriculas.id_parentezco', 'parentezco.id')
        ->join('participantes as participante', 'cursos_matriculas.id_participante', 'participante.id')
        ->join('catalogos_detalles as tipo_identificacion', 'cursos_matriculas.id_tipo_identificacion', 'tipo_identificacion.id')
        
        ->where([
                     ['cursos_matriculas.deleted_at', '=', null],
                     ['cursos_matriculas.id',$id]
                 ])
        ->selectRaw("cursos_matriculas.id,
					cursos_matriculas.id_curso,
					cursos_matriculas.id_participante,
					cursos_matriculas.nombres_participante,
					cursos_matriculas.apellidos_participante,
					cursos_matriculas.telefono,
					cursos_matriculas.correo,
					cursos_matriculas.id_tipo_identificacion,
					cursos_matriculas.documento_identidad,
					DATE_FORMAT(cursos_matriculas.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
					cursos_matriculas.edad,
					cursos_matriculas.id_estado_civil,
					cursos_matriculas.sexo,
					cursos_matriculas.id_pais,
					cursos_matriculas.id_departamento,
					cursos_matriculas.id_municipio,
					cursos_matriculas.direccion,
					cursos_matriculas.id_nivel_academico,
					cursos_matriculas.estudiando,
					cursos_matriculas.curso_estudiando,
					cursos_matriculas.trabajando,
					cursos_matriculas.lugar_trabajo,
					cursos_matriculas.salario,
					cursos_matriculas.referencia_nombre,
					cursos_matriculas.id_parentezco,
					cursos_matriculas.referencia_cedula,
					cursos_matriculas.referencia_telefono,
					cursos_matriculas.referencia_correo,
                    cursos_matriculas.calificacion,
                    cursos_matriculas.id_test_holland,
                    (select concat(hp.nombres,' ',hp.apellidos) as nombre_participante 
                    from holland_participante as hp 
                    inner join holland_respuesta as hr on hp.id = hr.participante_id  
                    where hr.id = cursos_matriculas.id_test_holland ) as nombre_participante_holland,
					catalogo_cursos.nombre as curso,
					departamento.nombre as departamento,
					estado_civil.nombre as estado_civil,
					municipio.nombre as municipio,
					nivel_academico.nombre as nivel_academico,
					pais.nombre as pais,
					parentezco.nombre as parentezco,
					concat(participante.nombres,' ', participante.apellidos) as participante,
                    tipo_identificacion.nombre as tipo_identificacion,
                    cursos_matriculas.egresado,
                    cursos_matriculas.comentarios", [])
        ->first();
        
        return response()->json(['curso_matricula'=>$matricula], 200);
    }
    public function update(Request $request)
    {
        
        //Validate inputs
        $cursos_matricula = $request["cursos_matricula"];
        $validator = Validator::make(
            $cursos_matricula,
            [
            'id_curso' => 'required|numeric',
            'nombres_participante' => 'required|max:50',
            'apellidos_participante' => 'required|max:50',
            'telefono' => 'required|max:20',
            'correo' => 'required|max:128',
            'id_estado_civil' => 'required|numeric',
            'sexo' => 'required|max:191',
            'id_pais' => 'required|numeric',
            'id_departamento' => 'required|numeric',
            'id_municipio' => 'required|numeric',
            'direccion' => 'required|max:255',
            'id_nivel_academico' => 'required|numeric',
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
            'fecha_nacimiento' => 'required',


        ]
        );
        $validator->validate();
        $update = $request["cursos_matricula"];
        $cursos_matricula = CursosMatricula::findOrFail($update["id"]);
        
        $cursos_matricula->documento_identidad  = str_replace('-', '', $cursos_matricula->documento_identidad);
        $cursos_matricula->documento_identidad  = str_replace('.', '', $cursos_matricula->documento_identidad);
        
        $cursos_matricula->id = $update['id'];
        $cursos_matricula->id_curso = $update['id_curso'];
        $cursos_matricula->id_participante = $update['id_participante'];
        $cursos_matricula->nombres_participante = $update['nombres_participante'];
        $cursos_matricula->apellidos_participante = $update['apellidos_participante'];
        $cursos_matricula->telefono = $update['telefono'];
        $cursos_matricula->correo = $update['correo'];
        $cursos_matricula->id_tipo_identificacion = $update['id_tipo_identificacion'];
        $cursos_matricula->documento_identidad = $update['documento_identidad'];
        $cursos_matricula->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $update['fecha_nacimiento']);
        $cursos_matricula->edad = $update['edad'];
        $cursos_matricula->id_estado_civil = $update['id_estado_civil'];
        $cursos_matricula->sexo = $update['sexo'];
        $cursos_matricula->id_pais = $update['id_pais'];
        $cursos_matricula->id_departamento = $update['id_departamento'];
        $cursos_matricula->id_municipio = $update['id_municipio'];
        $cursos_matricula->direccion = $update['direccion'];
        $cursos_matricula->id_nivel_academico = $update['id_nivel_academico'];
        $cursos_matricula->estudiando = $update['estudiando'];
        $cursos_matricula->curso_estudiando = $update['curso_estudiando'];
        $cursos_matricula->trabajando = $update['trabajando'];
        $cursos_matricula->lugar_trabajo = $update['lugar_trabajo'];
        $cursos_matricula->salario = $update['salario'];
        $cursos_matricula->referencia_nombre = $update['referencia_nombre'];
        $cursos_matricula->id_parentezco = $update['id_parentezco'];
        $cursos_matricula->referencia_cedula = $update['referencia_cedula'];
        $cursos_matricula->referencia_telefono = $update['referencia_telefono'];
        $cursos_matricula->referencia_correo = $update['referencia_correo'];
        $cursos_matricula->calificacion = $update['calificacion'];
        $cursos_matricula->id_test_holland = $update['id_test_holland'];
        $cursos_matricula->comentarios = $update['comentarios'];


        $cursos_matricula->save();
        


        $log = new BitacoraHelper();
        $log->log($request, 'Actualiza Matricula', 'Cursos Matriculas', $cursos_matricula->id);



        return response()->json(
            ["result"=> true],
            201
        );
    }
    public function destroy(Request $request, $id)
    {
        $cursos_matricula = CursosMatricula::findOrFail($id);
        $cursos_matricula->delete();
        
        $log = new BitacoraHelper();
        $log->log($request, 'Elimina Matricula', 'Cursos Matriculas', $cursos_matricula->id);
        return response()->json([], 204);
    }


    public function DescargarExcel(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());


        $filtro_curso=  "";
        if ( (!$request->has('mobile') || $request['mobile']==='false') && $request->has('id_curso') && $request['id_curso'] !=null ) {
            $filtro_curso =  " AND cursos_matriculas.id_curso =". $request['id_curso'];
        }


        $export = new CursosMatriculasExport($id_centros,$request->filtro,$filtro_curso);
        $log = new BitacoraHelper();

        $log->log($request, 'Exporta las inscripciones a excel', 'Inscripciones', null);
        return $export->download('inscripciones.xlsx');
    }
}
