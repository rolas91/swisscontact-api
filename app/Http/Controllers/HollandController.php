<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Centro;
use App\Models\HollandTest;
use Illuminate\Support\Str;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Functions\HollandDAL;
use App\Functions\formularios;
use App\Models\UsuariosCentro;
use App\Models\CursosMatricula;
use App\Models\HollandAdjetivo;
use App\Functions\BitacoraHelper;
use App\Models\HollandRespuestum;
use App\Models\HollandTestsCentro;
use Illuminate\Support\Facades\DB;
use App\Exports\TestsHollandExport;
use App\Models\HollandParticipante;
use Illuminate\Support\Facades\Log;
use App\Exports\HollandRespuestasExport;
use App\Models\HollandRespuestaAdjetivo;
use Illuminate\Support\Facades\Validator;

class HollandController extends Controller
{

    public function TestHollandActivo($token){
        $today = Carbon::now();
        $holland = HollandTest::where('token',$token)
        ->whereDate('fecha_fin', '>=', $today->format('Y-m-d') )
        ->first();
        $result = $holland !=null;
        return response()->json(['result' => $result]);
    }

    public function GetHollandTests()
    {
        $listado_holland = HollandTest::selectRaw('id as value,nombre as label', [])->orderBy('id', 'desc')->get();
        return response()->json(['resul'=>true, 'holland_tests' => $listado_holland], 200);
    }

    
    public function BuscarTestHollandParticipante(Request $request)
    {
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';

        $id_participante=null;
        $valor = $request['valor'];
        $tipo_busqueda = $request['tipo_busqueda'];


        if ($tipo_busqueda==="test_holland") {
            $respuesta = HollandTest::
        join('holland_respuesta', 'holland_respuesta.test_id', 'holland_tests.id')
        ->join('holland_participante', 'holland_participante.id', 'holland_respuesta.participante_id')
        ->where('holland_tests.id', $valor)
        ->selectRaw("holland_respuesta.id,concat(holland_participante.nombres,' ',holland_participante.apellidos) as nombre_participante,
        holland_participante.correo,
        holland_participante.telefono,
        holland_participante.cedula,
        holland_participante.personalidad
        ", [])
         ->orderBy($sortBy, $descending)
         ->paginate($rowsPerPage, ['*'], 'Page', $page);
        
            return response()->json(['resul'=>true, 'holland_respuestas' => $respuesta], 200);
        }

    
        
        if ($tipo_busqueda =="nombres") {
            $id_participante = HollandParticipante::whereRaw("nombres like '%$valor%' OR apellidos like '%$valor%'", [])->get()->pluck('id');
        } else {
            $id_participante = HollandParticipante::where($tipo_busqueda, $valor)->get()->pluck('id');
        }

        if (!$id_participante) {
            return response()->json(['result' => false,'message' => 'No existe ningún perfil con los datos proporcionados','data' => null], 200);
        }

          
        $respuesta =  HollandTest::
        join('holland_respuesta', 'holland_respuesta.test_id', 'holland_tests.id')
        ->join('holland_participante', 'holland_participante.id', 'holland_respuesta.participante_id')
        ->whereIn('holland_participante.id', $id_participante)
        ->selectRaw("holland_respuesta.id,concat(holland_participante.nombres,' ',holland_participante.apellidos) as nombre_participante,
        holland_participante.correo,
        holland_participante.telefono,
        holland_participante.cedula,
        holland_participante.personalidad
        ", [])
         ->orderBy($sortBy, $descending)
         ->paginate($rowsPerPage, ['*'], 'Page', $page);
        
    
        return response()->json(['resul'=>true, 'holland_respuestas' => $respuesta], 200);
    }


    public function StoreHollandTest(Request $request)
    {
        $holland_test = $request["holland_test"];
        $validator = Validator::make(
            $holland_test,
            [
            'nombre' => 'required|max:100',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ]
        );
        $validator->validate();


        $repetido = HollandTest::where('nombre',$holland_test['nombre'])->first();
        if($repetido){
            return response()->json([ 'result' => false, 'message' => "Ya existe un test de Holland creado para este curso, el test no puede ser creado"],200);
        }


        $holland_test= HollandTest::create([
            'nombre' => $holland_test['nombre'],
            'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $holland_test['fecha_inicio']),
            'fecha_fin' => Carbon::createFromFormat('d/m/Y', $holland_test['fecha_fin']),
            'usuario_creacion' => $request->user()->id,
            'token' => Str::random(16)
        ]);

       
        return response()->json(
            ['result' => true ],
            200
        );
    }


    public function DescargarRespuestasTestHollandExcel(Request $request)
    {
        $export = new HollandRespuestasExport($request->token);
        $log = new BitacoraHelper();
        $log->log($request, 'Exporta holland respuestas a excel', 'HollandRespuesta', null);
        return $export->download('centros.xlsx');
    }


    public function DescargarTestsHollandExcel(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        $page = 1;
        $rowsPerPage =  99999999;
        $sortBy =  'id';
        $descending = 'asc';
        $filtro =$request->filtro;


       
        $export = new TestsHollandExport($usuario, $filtro, $page, $rowsPerPage, $sortBy, $descending);

        $log = new BitacoraHelper();
        $log->log($request, 'Exporta tests de Holland ', 'Tests de Holland', null);

        return $export->download('test de holland.xlsx');
    }


    public function HollandCentros(Request $request)
    {
        $user = $request->user();
        $centros   = Centro::selectRaw("id, nombre,false checked", [])
                ->orderBy("nombre")
                ->get();

        $tests_centro = HollandTestsCentro::where('test_id', $request->id_test)->get();
        
        return response()->json(['centros'=> $centros ]);
    }

    public function IndexHollandTest(Request $request)
    {
        $usuario = $request->user();
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';

         $centro_filtro='';
        //Filtramos por un centro en específico
        if ($request['id_centro'] && $request['id_centro']) {
            $id_centro = $request['id_centro'];
            $centro_filtro = " AND catalogo_cursos.id_centro = $id_centro";
        }



        $dal = new HollandDAL($usuario, $request->filtro, $page, $rowsPerPage, $sortBy, $descending, $centro_filtro);
        $holland_tests =$dal->getAllTests();

        return  response()->json(["holland_tests"=> $holland_tests], 200);
    }

    public function GetRespuestas(Request $request)
    {
        $token = $request['token'];
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';
        $filtro = $request->filtro;

        $holland_respuestas = HollandTest::
        join('holland_respuesta', 'holland_respuesta.test_id', 'holland_tests.id')
        ->leftJoin('holland_participante', 'holland_participante.id', 'holland_respuesta.participante_id')
        ->where("holland_tests.token", $token)
        ->whereRaw("( concat(holland_participante.nombres,' ',holland_participante.apellidos) like '%$filtro%' OR 
        holland_participante.correo like '%$filtro%' OR
        holland_participante.telefono like '%$filtro%' OR
        holland_participante.cedula like '%$filtro%' OR
        holland_participante.personalidad like '%$filtro%' 
        
        )
        ")
        ->selectRaw("holland_respuesta.id,concat(holland_participante.nombres,' ',holland_participante.apellidos) as nombre_participante,
        holland_participante.correo,
        holland_participante.telefono,
        holland_participante.cedula,
        holland_participante.personalidad
        ", [])
          ->orderBy($sortBy, $descending)
        ->paginate($rowsPerPage, ['*'], 'Page', $page);
        return response()->json(["holland_respuestas"=> $holland_respuestas], 200);
    }


    public function getResultado(Request $request)
    {
        $token = $request['token'];
        $idResultado = $request['idResultado'];
        $holland_test = HollandTest::where('token', $token)->first();
        $holland_respuesta = HollandRespuestum::where([
            ['id_holland_test', $holland_test->id],
            ['id',$idResultado]
        ])->first();

        $parte_a = HollandRespuestaAdjetivo::
        join('holland_adjetivo', 'holland_adjetivo.id', 'holland_respuesta_adjetivos.adjetivo_id')
        ->where('holland_respuesta_adjetivos.respuesta_id', $holland_respuesta->id)->
        selectRaw('holland_respuesta_adjetivos.adjetivo_id,texto, dimension', [])->get();

        $a_d1 = $parte_a->where('dimension', '1')->count();
        $a_d2 = $parte_a->where('dimension', '2')->count();
        $a_d3 = $parte_a->where('dimension', '3')->count();
        $a_d4 = $parte_a->where('dimension', '4')->count();
        $a_d5 = $parte_a->where('dimension', '5')->count();
        $a_d6 = $parte_a->where('dimension', '6')->count();

        $b_d1 =0;
        $b_d2 =0;
        $b_d3 = 0;
        $b_d4 = 0;
        $b_d5 = 0;
        $b_d6 = 0;

        $c_d1 =0;
        $c_d2 =0;
        $c_d3 = 0;
        $c_d4 = 0;
        $c_d5 = 0;
        $c_d6 = 0;

        $d_d1 = 0;
        $d_d2 = 0;
        $d_d3 = 0;
        $d_d4 = 0;
        $d_d5 = 0;
        $d_d6 = 0;


        $parte_b = json_decode($holland_respuesta->parte_b);
        $parte_c = json_decode($holland_respuesta->parte_c);

        $parte_d_1 = $holland_respuesta->parte_d_1;
        $parte_d_2 = $holland_respuesta->parte_d_2;
        $parte_d_3 = $holland_respuesta->parte_d_3;
        $parte_d_4 = $holland_respuesta->parte_d_4;
        $parte_d_5 = $holland_respuesta->parte_d_5;

        foreach ($parte_b as $key => $col) {
            $an = collect($col->columns)->where('checked', true)->first()->value;

            if ($key == 0 and ($an == 2 or $an == 3)) {
                $b_d1 += 1;
            }

            if ($key == 1 and ($an == 1)) {
                $b_d6 += 1;
            }

            if ($key == 2 and ($an == 1)) {
                $b_d4 += 1;
            }

            if ($key == 3 and ($an == 1)) {
                $b_d4 += 1;
            }

            if ($key == 4 and ($an == 1)) {
                $b_d3 += 1;
            }

            if ($key == 5 and ($an == 1)) {
                $b_d6 += 1;
            }

            if ($key == 6 and ($an == 1)) {
                $b_d5 += 1;
            }

            if ($key == 7 and ($an == 1)) {
                $b_d3 += 1;
            }

            if ($key == 8 and ($an == 1)) {
                $b_d2 += 1;
            }

            if ($key == 9 and ($an == 1)) {
                $b_d1 += 1;
            }

            if ($key == 10 and ($an == 1)) {
                $b_d6 += 1;
            }

            if ($key == 11 and ($an == 1)) {
                $b_d5 += 1;
            }

            if ($key == 12 and ($an == 1)) {
                $b_d2 += 1;
            }

            if ($key == 13 and ($an == 1)) {
                $b_d2 += 1;
            }

            if ($key == 14 and ($an == 1)) {
                $b_d5 += 1;
            }

            if ($key == 15 and ($an == 2 or $an == 3)) {
                $b_d1 += 1;
            }

            if ($key == 16 and ($an == 1)) {
                $b_d3 += 1;
            }

            if ($key == 17 and ($an == 1)) {
                $b_d4 += 1;
            }
        }


        # Evaluando C

        $c_d1 = 0;
        $c_d2 = 0;
        $c_d3 = 0;
        $c_d4 = 0;
        $c_d5 = 0;
        $c_d6 = 0;

        $valores_c = array();
        foreach ($parte_c as $key => $col) {
            $an = collect($col->columns)->where('checked', true)->first()->value;
            array_push($valores_c, $an);

            if ($key == 0 and ($an == 1)) {
                $c_d4 += 1;
            }

            if ($key == 1 and ($an == 1)) {
                $c_d1 += 1;
            }

            if ($key == 2 and ($an == 1)) {
                $c_d3 += 1;
            }

            if ($key == 3 and ($an == 1)) {
                $c_d2 += 1;
            }

            if ($key == 4 and ($an == 1)) {
                $c_d1 += 1;
            }

            if ($key == 5 and ($an == 1)) {
                $c_d5 += 1;
            }

            if ($key == 5 and ($an == 1)) {
                $c_d5 += 1;
            }

            if ($key == 7 and ($an == 1)) {
                $c_d4 += 1;
            }

            if ($key == 8 and ($an == 1)) {
                $c_d2 += 1;
            }

            if ($key == 9 and ($an == 1)) {
                $c_d2 += 1;
            }

            if ($key == 10 and ($an == 1)) {
                $c_d6 += 1;
            }

            if ($key == 11 and ($an == 2 or $an == 3)) {
                $c_d1 += 1;
            }

            if ($key == 12 and ($an == 1)) {
                $c_d4 += 1;
            }

            if ($key == 13 and ($an == 1)) {
                $c_d3 += 1;
            }

            if ($key == 14 and ($an == 1)) {
                $c_d6 += 1;
            }

            if ($key == 15 and ($an == 2)) {
                $c_d6 += 1;
            }

            if ($key == 16 and ($an == 1)) {
                $c_d5 += 1;
            }

            if ($key == 17 and ($an == 1)) {
                $c_d3 += 1;
            }
        }

        if ($parte_d_1 == 1) {
            $d_d1 += 1;
        }

        if ($parte_d_1 == 2) {
            $d_d2 += 1;
        }

        if ($parte_d_1 == 3) {
            $d_d3 += 1;
        }

        if ($parte_d_1 == 4) {
            $d_d4 += 1;
        }

        if ($parte_d_1 == 5) {
            $d_d5 += 1;
        }

        if ($parte_d_1 == 6) {
            $d_d6 += 1;
        }

        if ($parte_d_2 == 1) {
            $d_d1 += 1;
        }

        if ($parte_d_2 == 2) {
            $d_d2 += 1;
        }

        if ($parte_d_2 == 3) {
            $d_d3 += 1;
        }

        if ($parte_d_2 == 4) {
            $d_d4 += 1;
        }

        if ($parte_d_2 == 5) {
            $d_d5 += 1;
        }

        if ($parte_d_2 == 6) {
            $d_d6 += 1;
        }

        if ($parte_d_3 == 1) {
            $d_d1 += 1;
        }

        if ($parte_d_3 == 2) {
            $d_d2 += 1;
        }

        if ($parte_d_3 == 3) {
            $d_d3 += 1;
        }

        if ($parte_d_3 == 4) {
            $d_d4 += 1;
        }

        if ($parte_d_3 == 5) {
            $d_d5 += 1;
        }

        if ($parte_d_3 == 6) {
            $d_d6 += 1;
        }

        if ($parte_d_4 == 1) {
            $d_d1 += 1;
        }

        if ($parte_d_4 == 2) {
            $d_d2 += 1;
        }

        if ($parte_d_4 == 3) {
            $d_d3 += 1;
        }

        if ($parte_d_4 == 4) {
            $d_d4 += 1;
        }

        if ($parte_d_4 == 5) {
            $d_d5 += 1;
        }

        if ($parte_d_4 == 6) {
            $d_d6 += 1;
        }

        if ($parte_d_5 == 1) {
            $d_d1 += 1;
        }

        if ($parte_d_5 == 2) {
            $d_d2 += 1;
        }

        if ($parte_d_5 == 3) {
            $d_d3 += 1;
        }

        if ($parte_d_5 == 4) {
            $d_d4 += 1;
        }

        if ($parte_d_5 == 5) {
            $d_d5 += 1;
        }

        if ($parte_d_5 == 6) {
            $d_d6 += 1;
        }

        $realista = $a_d1 + $b_d1 + $c_d1 + $d_d1;
        $investigador = $a_d2 + $b_d2 + $c_d2 + $d_d2;
        $social = $a_d3 + $b_d3 + $c_d3 + $d_d3;
        $convencional = $a_d4 + $b_d4 + $c_d4 + $d_d4;
        $emprendedor = $a_d5 + $b_d5 + $c_d5 + $d_d5;
        $artistico = $a_d6 + $b_d6 + $c_d6 + $d_d6;

        $personalidades = array(
                [ 'nombre'=>  'Realista', 'value' =>  $realista],
                [  'nombre' => 'Investigador', 'value' => $investigador],
                [  'nombre' => 'Social', 'value' => $social],
                [  'nombre' => 'Convencional', 'value' => $convencional],
                [  'nombre'=> 'Emprendedor', 'value' => $emprendedor],
                [ 'nombre' => 'Artístico',  'value' => $artistico]
            );

        $col_per =collect($personalidades);
        $sorted = $col_per->SortByDesc('value');
        $personalidades = $sorted->values()->all();

        $cuadro_resumen = [[$a_d1, $a_d2, $a_d3, $a_d4, $a_d5, $a_d6],
            [$b_d1, $b_d2, $b_d3, $b_d4, $b_d5, $b_d6],
            [$c_d1, $c_d2, $c_d3, $c_d4, $c_d5, $c_d6],
            [$d_d1, $d_d2, $d_d3, $d_d4, $d_d5, $d_d6]];




        return response()->json([
            'a_d1' => $a_d1,
            'a_d2' => $a_d2,
            'a_d3'  =>$a_d3,
            'a_d4'  =>$a_d4,
            'a_d5' => $a_d5,
            'a_d6' => $a_d6,
            'parte_a' => $parte_a,
            'parte_b' => $parte_b,
            'parte_c' => $parte_c,
            'parte_d_1' => $parte_d_1,
            'parte_d_2' => $parte_d_2,
            'parte_d_3' => $parte_d_3,
            'parte_d_4' => $parte_d_4,
            'parte_d_5' => $parte_d_5,
            'realista' => $realista,
            'investigador' => $investigador,
            'social' => $social,
            'convencional' => $convencional,
            'emprendedor' => $emprendedor,
            'artistico' => $artistico,
            'personalidades' =>$personalidades,
            'holland_respuesta' => $holland_respuesta,
            'cuadro_resumen' => json_encode($cuadro_resumen)]);
    }

  



    public function UpdateHollandTest(Request $request)
    {
        $update = $request['holland_test'];
        $holland_test = HollandTest::find($update['id']);
        $holland_test->nombre = $update['nombre'];
        $holland_test->fecha_inicio =  Carbon::createFromFormat('d/m/Y', $update['fecha_inicio']) ;
        $holland_test->fecha_fin = Carbon::createFromFormat('d/m/Y', $update['fecha_fin']);
        
        $holland_test->save();
        return response()->json([ 'holland_test' => $holland_test], 201);
    }


    public function DestroyHollandTest(Request $request)
    {
        $holland_test = HollandTest::find($request['id']);

        $cantidad_respuestas = HollandRespuestum::where('id_holland_test', $holland_test->id)->count();
        if ($cantidad_respuestas>0) {
            //error ya tenemos respuestas no podemos eliminar el test
            return response()->json(['result' => false,'message' => 'Ya existen respuestas vinculadas a este formulario, por lo tanto no se puede eliminar',500]);
        }
        $holland_test->delete();

        $log = new BitacoraHelper();
        $log->log($request, 'Elimina Test de Holland', 'Holland Test', $holland_test->id);
        return response()->json(['result'=> true], 200);
    }


    public function editHollandTest($id)
    {
        $holland_test = HollandTest::
        join('usuarios as usuario_creacion', 'holland_tests.usuario_creacion', 'usuario_creacion.id')
        ->where('holland_tests.id', $id)
        ->selectRaw('holland_tests.id,
					holland_tests.nombre,
					DATE_FORMAT(holland_tests.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(holland_tests.fecha_fin, "%d/%m/%Y") as fecha_fin,
                    holland_tests.usuario_creacion,
                    holland_tests.token,
					usuario_creacion.nombre as usuario_creacion', [])->first();
        return response()->json(['holland_test' => $holland_test], 200);
    }


    public function verficarIdentidadHolland(Request $request)
    {
        return formularios::verficarIdentidad($request);
    }

    public function index()
    {
        $parte_a = HollandAdjetivo::all();

        foreach ($parte_a as $key => $part) {
            $parte_a[$key]->checked = false;
        }

        return response()->json(['parte_a' => $parte_a], 200);
    }

    public function store(Request $request)
    {
        $matricula = CursosMatricula::where([
            ['id_curso', $request['id_curso']],
            [$request['tipo_identidad'], $request['doc_identidad']],
        ])->first();


        $id_participante=null;
        if ($matricula) {
            $id_participante = $matricula->id_participante;
        }
        DB::beginTransaction();

        try {
            $holland_test = HollandTest::where('token', $request['token'])->first();
            $participante = $request['participante'];

            $holland_participante =  HollandParticipante::create([
                'creado' => Carbon::now(),
                'test_id' => $holland_test->id,
                'id_participante' => $id_participante,
                'correo' => $participante['correo'],
                'nombres' => $participante['nombres'],
                'apellidos' => $participante['apellidos'],
                'telefono' => $participante['telefono'],
                'cedula' => $participante['cedula'],
                'token' =>null,
                'invitacion_enviada' => Carbon::now(),
                'personalidad' => ''
            ]);

            # Evaluando A
            $parte_a = collect($request['parte_a']);

            $a_d1 = $parte_a->where('dimension', '1')->where('checked', true)->count();
            $a_d2 = $parte_a->where('dimension', '2')->where('checked', true)->count();
            $a_d3 = $parte_a->where('dimension', '3')->where('checked', true)->count();
            $a_d4 = $parte_a->where('dimension', '4')->where('checked', true)->count();
            $a_d5 = $parte_a->where('dimension', '5')->where('checked', true)->count();
            $a_d6 = $parte_a->where('dimension', '6')->where('checked', true)->count();


            # Evaluando B
            $parte_b = collect($request['parte_b']);

            $b_d1 = 0;
            $b_d2 = 0;
            $b_d3 = 0;
            $b_d4 = 0;
            $b_d5 = 0;
            $b_d6 = 0;


            foreach ($parte_b as $key => $col) {
                $an = collect($col['columns'])->where('checked', true)->first()['value'];

                if ($key == 0 and ($an == 2 or $an == 3)) {
                    $b_d1 += 1;
                }

                if ($key == 1 and ($an == 1)) {
                    $b_d6 += 1;
                }

                if ($key == 2 and ($an == 1)) {
                    $b_d4 += 1;
                }

                if ($key == 3 and ($an == 1)) {
                    $b_d4 += 1;
                }

                if ($key == 4 and ($an == 1)) {
                    $b_d3 += 1;
                }

                if ($key == 5 and ($an == 1)) {
                    $b_d6 += 1;
                }

                if ($key == 6 and ($an == 1)) {
                    $b_d5 += 1;
                }

                if ($key == 7 and ($an == 1)) {
                    $b_d3 += 1;
                }

                if ($key == 8 and ($an == 1)) {
                    $b_d2 += 1;
                }

                if ($key == 9 and ($an == 1)) {
                    $b_d1 += 1;
                }

                if ($key == 10 and ($an == 1)) {
                    $b_d6 += 1;
                }

                if ($key == 11 and ($an == 1)) {
                    $b_d5 += 1;
                }

                if ($key == 12 and ($an == 1)) {
                    $b_d2 += 1;
                }

                if ($key == 13 and ($an == 1)) {
                    $b_d2 += 1;
                }

                if ($key == 14 and ($an == 1)) {
                    $b_d5 += 1;
                }

                if ($key == 15 and ($an == 2 or $an == 3)) {
                    $b_d1 += 1;
                }

                if ($key == 16 and ($an == 1)) {
                    $b_d3 += 1;
                }

                if ($key == 17 and ($an == 1)) {
                    $b_d4 += 1;
                }
            }


            # Evaluando C
            $parte_c = collect($request['parte_c']);

            $c_d1 = 0;
            $c_d2 = 0;
            $c_d3 = 0;
            $c_d4 = 0;
            $c_d5 = 0;
            $c_d6 = 0;

            $valores_c = array();
            foreach ($parte_c as $key => $col) {
                $an = collect($col['columns'])->where('checked', true)->first()['value'];
                array_push($valores_c, $an);

                if ($key == 0 and ($an == 1)) {
                    $c_d4 += 1;
                }

                if ($key == 1 and ($an == 1)) {
                    $c_d1 += 1;
                }

                if ($key == 2 and ($an == 1)) {
                    $c_d3 += 1;
                }

                if ($key == 3 and ($an == 1)) {
                    $c_d2 += 1;
                }

                if ($key == 4 and ($an == 1)) {
                    $c_d1 += 1;
                }

                if ($key == 5 and ($an == 1)) {
                    $c_d5 += 1;
                }

                if ($key == 5 and ($an == 1)) {
                    $c_d5 += 1;
                }

                if ($key == 7 and ($an == 1)) {
                    $c_d4 += 1;
                }

                if ($key == 8 and ($an == 1)) {
                    $c_d2 += 1;
                }

                if ($key == 9 and ($an == 1)) {
                    $c_d2 += 1;
                }

                if ($key == 10 and ($an == 1)) {
                    $c_d6 += 1;
                }

                if ($key == 11 and ($an == 2 or $an == 3)) {
                    $c_d1 += 1;
                }

                if ($key == 12 and ($an == 1)) {
                    $c_d4 += 1;
                }

                if ($key == 13 and ($an == 1)) {
                    $c_d3 += 1;
                }

                if ($key == 14 and ($an == 1)) {
                    $c_d6 += 1;
                }

                if ($key == 15 and ($an == 2)) {
                    $c_d6 += 1;
                }

                if ($key == 16 and ($an == 1)) {
                    $c_d5 += 1;
                }

                if ($key == 17 and ($an == 1)) {
                    $c_d3 += 1;
                }
            }

            # Evaluando D
            $parte_d_1 = $request['parte_d1']['value'];
            $parte_d_2 = $request['parte_d2']['value'];
            $parte_d_3 = $request['parte_d3']['value'];
            $parte_d_4 = $request['parte_d4']['value'];
            $parte_d_5 = $request['parte_d5']['value'];


            $d_d1 = 0;
            $d_d2 = 0;
            $d_d3 = 0;
            $d_d4 = 0;
            $d_d5 = 0;
            $d_d6 = 0;

            if ($parte_d_1 == 1) {
                $d_d1 += 1;
            }

            if ($parte_d_1 == 2) {
                $d_d2 += 1;
            }

            if ($parte_d_1 == 3) {
                $d_d3 += 1;
            }

            if ($parte_d_1 == 4) {
                $d_d4 += 1;
            }

            if ($parte_d_1 == 5) {
                $d_d5 += 1;
            }

            if ($parte_d_1 == 6) {
                $d_d6 += 1;
            }

            if ($parte_d_2 == 1) {
                $d_d1 += 1;
            }

            if ($parte_d_2 == 2) {
                $d_d2 += 1;
            }

            if ($parte_d_2 == 3) {
                $d_d3 += 1;
            }

            if ($parte_d_2 == 4) {
                $d_d4 += 1;
            }

            if ($parte_d_2 == 5) {
                $d_d5 += 1;
            }

            if ($parte_d_2 == 6) {
                $d_d6 += 1;
            }

            if ($parte_d_3 == 1) {
                $d_d1 += 1;
            }

            if ($parte_d_3 == 2) {
                $d_d2 += 1;
            }

            if ($parte_d_3 == 3) {
                $d_d3 += 1;
            }

            if ($parte_d_3 == 4) {
                $d_d4 += 1;
            }

            if ($parte_d_3 == 5) {
                $d_d5 += 1;
            }

            if ($parte_d_3 == 6) {
                $d_d6 += 1;
            }

            if ($parte_d_4 == 1) {
                $d_d1 += 1;
            }

            if ($parte_d_4 == 2) {
                $d_d2 += 1;
            }

            if ($parte_d_4 == 3) {
                $d_d3 += 1;
            }

            if ($parte_d_4 == 4) {
                $d_d4 += 1;
            }

            if ($parte_d_4 == 5) {
                $d_d5 += 1;
            }

            if ($parte_d_4 == 6) {
                $d_d6 += 1;
            }

            if ($parte_d_5 == 1) {
                $d_d1 += 1;
            }

            if ($parte_d_5 == 2) {
                $d_d2 += 1;
            }

            if ($parte_d_5 == 3) {
                $d_d3 += 1;
            }

            if ($parte_d_5 == 4) {
                $d_d4 += 1;
            }

            if ($parte_d_5 == 5) {
                $d_d5 += 1;
            }

            if ($parte_d_5 == 6) {
                $d_d6 += 1;
            }


            $realista = $a_d1 + $b_d1 + $c_d1 + $d_d1;
            $investigador = $a_d2 + $b_d2 + $c_d2 + $d_d2;
            $social = $a_d3 + $b_d3 + $c_d3 + $d_d3;
            $convencional = $a_d4 + $b_d4 + $c_d4 + $d_d4;
            $emprendedor = $a_d5 + $b_d5 + $c_d5 + $d_d5;
            $artistico = $a_d6 + $b_d6 + $c_d6 + $d_d6;
            $cuadro_resumen = [[$a_d1, $a_d2, $a_d3, $a_d4, $a_d5, $a_d6],
            [$b_d1, $b_d2, $b_d3, $b_d4, $b_d5, $b_d6],
            [$c_d1, $c_d2, $c_d3, $c_d4, $c_d5, $c_d6],
            [$d_d1, $d_d2, $d_d3, $d_d4, $d_d5, $d_d6]];

            $respuesta = HollandRespuestum::create([
                'id_holland_test' => $holland_test->id,
                'creado' => Carbon::now(),
                'actualizado' => Carbon::now(),
                'tiempo' => 0,
                'participante_id' => $holland_participante->id,
                'parte_b' => json_encode($request['parte_b']),
                'parte_c' => json_encode($request['parte_c']),
                'parte_d_1' => $parte_d_1,
                'parte_d_2' => $parte_d_2,
                'parte_d_3' => $parte_d_3,
                'parte_d_4' => $parte_d_4,
                'parte_d_5' => $parte_d_5,
                'test_id' => $holland_test->id,
                'cuadro_resumen' => json_encode($cuadro_resumen),
                'hora_finalizado' => Carbon::now(),
            ]);


            $parte_a= $request['parte_a'];

            foreach ($parte_a as $key => $adjetivo) {
                try {
                    if (!gettype($adjetivo) === 'array') {
                        if ($adjetivo->checked) {
                            HollandRespuestaAdjetivo::create([
                                'respuesta_id' => $respuesta->id,
                                'adjetivo_id' => $adjetivo->id,
                            ]);
                        }
                    } else {
                        if ($adjetivo['checked']) {
                            HollandRespuestaAdjetivo::create([
                                'respuesta_id' => $respuesta->id,
                                'adjetivo_id' => $adjetivo['id'],
                            ]);
                        }
                    }
                } catch (\Throwable $th) {
                    throw $th;

                    return response()->json([ 'error' , 'adjetivo' => $adjetivo , 'tipo' => gettype($adjetivo)]);
                }
            }

            $personalidad = array(
                   'realista' => $realista,
                   'investigador' => $investigador,
                   'social' => $social,
                   'convencional' => $convencional,
                   'emprendedor' => $emprendedor,
                   'artistico' => $artistico
            );

            arsort($personalidad);
            $col_personlidad = collect(array_keys($personalidad));
            $personalidad =$col_personlidad->first();
            $holland_participante->personalidad = $personalidad;
            $holland_participante->save();

            DB::commit();

            return response()->json(['result'=> true,'message'=> 'Datos guardados correctamente']);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
