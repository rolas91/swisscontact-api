<?php

namespace  App\Functions;

use Exception;
use App\Usuario;
use Carbon\Carbon;
use App\Models\Curso;
use App\Models\Formulario;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\CursosMatricula;
use App\Models\CatalogosDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FormulariosRespuesta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\FormulariosRespuestasCampo;
use App\Models\HollandRespuestum;

class formularios
{
    public static function verficarIdentidadHolland(Request $request)
    {
        $tipo_identidad = $request['tipo_identidad'];
        $doc_identidad = $request['doc_identidad'];
        $id_centro = $request['id_centro'];
        $id_curso = $request['id_curso'];

  
        $curso = Curso::
        join('catalogo_cursos', 'cursos.id_curso', 'catalogo_cursos.id')->
        where([
            ['cursos.id', $id_curso],
            ['id_centro', $id_centro]
        ])->first();

        $matricula = CursosMatricula::where([
            ['id_curso', $id_curso],
            [$tipo_identidad, $doc_identidad]
        ])->get();

        //Si hay mas de una matricula para el tipo de dato seleccionado devolvemos los registros que coinciden
        if ($matricula->count()>1) {
            if ($request['id_participante'] && $request['mostrar_multiples'] ===false) {
                $id_participante = $request['id_participante'] ;
                $formulario_respuesta = HollandRespuestum::where([
                    ['participante_id',$id_participante],
                    ['test_id',1]
                ])->first();

                if ($formulario_respuesta) {
                    return response()->json(['result' => false, 'code' =>  'completado', 'message' => 'EL correo proporcionado ya ha contestado este formulario'], 200);
                }
            }

            $coincidencias = [];
            foreach ($matricula as $key => $value) {
                array_push($coincidencias, [ 'label' =>  $value->nombres_participante . ' ' . $value->apellidos_participante,
                    'value' => $value->id_participante
                 ]);
            }

            return response()->json(['result'=>false, 'code'=> 'multiples_coincidencias','multiples_coincidencias' => $coincidencias], 200);
        }

        $matricula = $matricula->first();

        if (!$curso) {
            return response()->json(['result' => false, 'code' =>  'no_curso', 'message' => 'Selecciona el centro y un curso'], 200);
        }
     

        if (!$matricula) {
            return response()->json(['result' => false, 'code' =>  'no_matricula', 'message' => "No encontramos ningún participante con los datos proporcionados para realizar este formulario, por favor revise que haya escrito correctamente sus datos"], 200);
        }


        return response()->json(['result' => true, 'participante' => [ 'id_participante' => $matricula->id_participante, 'nombre' => $matricula->nombres_participante . ' ' . $matricula->apellidos_participante]], 200);
    }




    public static function verficarIdentidad(Request $request)
    {
        $tipo_identidad = $request['tipo_identidad'];
        $doc_identidad = $request['doc_identidad'];
        $id_centro = $request['id_centro'];
        $id_curso = $request['id_curso'];
        $slug = $request['slug'];
        $formulario = Formulario::where('url', $slug)->first();

        if ($formulario->id_modo ==5604) {
            $formulario_respuesta = FormulariosRespuesta::where(
                [
                    ['id_formulario', $formulario->id],
                    ['correo_participante', $request['correo_participante']]
                ]
            )->first();

            if ($formulario_respuesta) {
                return response()->json(['result' => false, 'code' =>  'completado', 'message' => 'EL correo proporcionado ya ha contestado este formulario'], 200);
            }

            return response()->json([ 'result'=> true], 200);
        }

        $curso = Curso::
        join('catalogo_cursos', 'cursos.id_curso', 'catalogo_cursos.id')->
        where([
            ['cursos.id', $id_curso],
            ['id_centro', $id_centro]
        ])->first();

        $matricula = CursosMatricula::where([
            ['id_curso', $id_curso],
            [$tipo_identidad, $doc_identidad]
        ])->get();

        //Si hay mas de una matricula para el tipo de dato seleccionado devolvemos los registros que coinciden
        if ($matricula->count()>1) {
            if ($request['id_participante'] && $request['mostrar_multiples'] ===false) {
                $id_participante = $request['id_participante'] ;
                $formulario_respuesta = FormulariosRespuesta::where([
                    ['id_formulario', $formulario->id],
                    ['id_participante', $id_participante]
                ])->first();

                if ($formulario_respuesta) {
                    return response()->json(['result' => false, 'code' =>  'completado', 'message' => 'EL correo proporcionado ya ha contestado este formulario'], 200);
                }
            }

            $coincidencias = [];
            foreach ($matricula as $key => $value) {
                array_push($coincidencias, [ 'label' =>  $value->nombres_participante . ' ' . $value->apellidos_participante,
                    'value' => $value->id_participante
                 ]);
            }

            return response()->json(['result'=>false, 'code'=> 'multiples_coincidencias','multiples_coincidencias' => $coincidencias], 200);
        }

        $matricula = $matricula->first();

        if (!$curso) {
            return response()->json(['result' => false, 'code' =>  'no_curso', 'message' => 'Selecciona el centro y un curso'], 200);
        }

        if (!$formulario) {
            return response()->json(['result' => false, 'code' =>  'no_formulario', 'message' => 'No se ha encontrado ningún Formulario, revise que la url proporcionada sea correcta'], 200);
        }

        if (!$matricula) {
            return response()->json(['result' => false, 'code' =>  'no_matricula', 'message' => "No encontramos ningún participante con los datos proporcionados para realizar este formulario, por favor revise que haya escrito correctamente sus datos"], 200);
        }

        

        $formulario_respuesta = FormulariosRespuesta::where([
            ['id_formulario', $formulario->id],
            ['id_participante', $matricula->id_participante]
        ])->first();

        if ($formulario_respuesta) {
            return response()->json(['result' => false, 'code' =>  'completado', 'message' => 'EL correo proporcionado ya ha contestado este formulario'], 200);
        }


        return response()->json(['result' => true, 'participante' => [ 'id_participante' => $matricula->id_participante, 'nombre' => $matricula->nombres_participante . ' ' . $matricula->apellidos_participante]], 200);
    }

    public static function importarDatos()
    {
        $detalle_encuestas = DB::select(DB::raw('select * from view_encuestas'));
        $encuestas =  DB::select(DB::raw("select  (select id from centros where nombre = centro  collate 'utf8mb4_unicode_ci') as id_centro, depto,
        case when  
                (select id from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(encuestas2.depto)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select id from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(encuestas2.depto)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select id_departamento from centros where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(encuestas2.centro)  limit 1) end as id_departamento,
                
                
                 case 
					when  
                (select nombre from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(encuestas2.depto)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select nombre from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(encuestas2.depto)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select dpt.nombre from centros inner join catalogos_detalles as dpt on centros.id_departamento = dpt.id where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(encuestas2.centro)  limit 1) 
                end as departamento,
                
                
                case when  
                (select id from catalogos_detalles where  catalogos_detalles.nombre  = trim(encuestas2.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select id from catalogos_detalles where  catalogos_detalles.nombre  = trim(encuestas2.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select id_municipio from centros where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(encuestas2.centro)  limit 1) end as id_municipio,
                
                 case when  
                (select nombre from catalogos_detalles where  catalogos_detalles.nombre  = trim(encuestas2.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select nombre from catalogos_detalles where  catalogos_detalles.nombre  = trim(encuestas2.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select mun.nombre from centros inner join catalogos_detalles mun on centros.id_municipio = mun.id where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(encuestas2.centro)  limit 1) end as municipio,
                
                
         SUBSTRING_INDEX(trim(n_participante),' ',case when  LENGTH(n_participante) - LENGTH(REPLACE(n_participante, ' ', '')) =1 then 1 else  LENGTH(n_participante) - LENGTH(REPLACE(n_participante, ' ', ''))-1 end  ) as nombres,
                SUBSTRING_INDEX(trim(n_participante), ' ', case when LENGTH(n_participante) - LENGTH(REPLACE(n_participante, ' ', '')) =1 then -1 else -2 end) as apellidos,n_participante,
                n_cedula as cedula,f_nacim fecha_nacimiento,edad,est_civil estado_civil,sexo,tel_partic as telefono,
                encuestas2.direccion,municipio as muni,depto,persona_adic,parentesco,tel_per2 as tel_adicional,
                grado,nivel,trabaja,encuestas2.nombre as lugar_trabajo,ingreso_mes,encuestas2.centro, encuestas2.curso,encuestas2.fencuesta,sector,categoria,(select id from catalogos_detalles where id_catalogo=34 and sector =catalogos_detalles.nombre COLLATE 'utf8mb4_general_ci') as id_sector,
                (select id from catalogos_detalles where id_catalogo=35 and categoria =catalogos_detalles.nombre COLLATE 'utf8mb4_general_ci') as id_categoria
                from encuestas2;
        "));

        $estados_civil = [];
        array_push($estados_civil, ['id' => 5540, 'estado' => 'Soltero/a']);
        array_push($estados_civil, ['id' => 5541, 'estado' => 'Casado/a']);
        array_push($estados_civil, ['id' => 5542, 'estado' => 'Unión de hecho estable']);
        array_push($estados_civil, ['id' => 5540, 'estado' => 'no responde']);

        $nivel_educacion = [];
        array_push($nivel_educacion, ['id' => 5489, 'nivel' => 'Primaria']);
        array_push($nivel_educacion, ['id' => 5491, 'nivel' => 'Secundaria']);
        array_push($nivel_educacion, ['id' => 5493, 'nivel' => 'Técnico']);
        array_push($nivel_educacion, ['id' => 5495, 'nivel' => 'Universidad']);
        array_push($nivel_educacion, ['id' => 5489, 'nivel' => 'No responde']);


        try {
            DB::beginTransaction();
            foreach ($encuestas as $key => $encuesta) {
                //$campos =DB::select(DB::raw("select * from view_encuestas where identidad ='$identidad'"));


                $id_estado_civil = null;
                foreach ($estados_civil as $item) {
                    if ($item['estado'] == $encuesta->estado_civil) {
                        $id_estado_civil = $item['id'];
                        break;
                    }
                }

                $id_nivel_educacion = null;
                foreach ($nivel_educacion as $item) {
                    if ($item['nivel'] == $encuesta->nivel) {
                        $id_nivel_educacion = $item['id'];
                        break;
                    }
                }

                $id_parentesco = null;
                switch ($encuesta->parentesco) {
                    case "mama":
                    case "MADRES":
                    case "madre":
                    case "hermana":
                    case "prima":
                    case "Hermano":

                    case "mi madre":
                    case "PADRE":
                    case "papá":
                    case "padres":
                    case "a mi papa":
                        $id_parentesco = 5554;
                        break;
                    case "hemano":
                    case "hermana":
                        $id_parentesco = 5555;
                        break;
                    case "familiar":
                    case "tia":
                    case "abuela":
                    case "abuelo":
                    case "abuelita":
                    case "sobrina":
                    case "sobrino":
                    case "prima":
                    case "primo":
                    case "Primo":
                    case "hija":
                    case "HIJO":
                    case "PADRASTRO":
                    case "Suegro":
                    case "YERNO":


                        $id_parentesco = 5556;
                        break;

                    case "compañera de vida":
                    case "esposa":
                    case "Esposo":
                    case "Esposo.":
                    case "conyugue":
                    case "casado":

                        $id_parentesco = 5570;
                        break;

                    case "":
                    case " ":
                    case "amigo":
                    case "amiga":
                    case "CONOCIDO":
                    case "Compañero":
                    case "JEFE":
                    case "JEFA":
                    case "TUTOR":
                    case "Maria jose galeano Salcedo":
                    case "moreno":
                    case "COMPAÑERO DE TRABAJO":
                    case "nada":
                    case "otro":
                    case "tutora":
                        $id_parentesco = 5557;
                }

                try {
                    $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $encuesta->fecha_nacimiento);
                } catch (\Throwable $th) {
                    $fecha_nacimiento =Carbon::now();
                }

                $parentesco = CatalogosDetalle::find($id_parentesco);

                if (!$parentesco) {
                    $parentesco =  CatalogosDetalle::find(5556);
                }
               
                $participante = Participante::create([
                    'nombres' => $encuesta->nombres,
                    'apellidos' => $encuesta->apellidos,
                    'telefono' => $encuesta->telefono,
                    'correo' => null,
                    'id_tipo_identificacion' => 5496,
                    'documento_identidad' => $encuesta->cedula,
                    'menor_edad' => ((int) $encuesta->edad < 16),
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'id_estado_civil' => $id_estado_civil,
                    'sexo' => $encuesta->sexo,
                    'id_pais' => 1438,
                    'id_departamento' => $encuesta->id_departamento,
                    'id_ciudad' => $encuesta->id_municipio,
                    'direccion' => $encuesta->direccion,
                    'id_nivel_educacion' => $id_nivel_educacion,
                    'estudiando' => false,
                    'curso_estudiando' => null,
                    'trabajando' => strtolower($encuesta->trabaja) == "si",
                    'lugar_trabajo' => $encuesta->lugar_trabajo,
                    'salario' => str_replace(",", "", $encuesta->ingreso_mes) == "" ? 0 :  (int)str_replace(",", "", $encuesta->ingreso_mes),
                    'referencia_nombre' =>  $encuesta->persona_adic,
                    'id_parentezco' => $id_parentesco,
                    'referencia_cedula' => null,
                    'referencia_telefono' => $encuesta->tel_adicional,
                    'referencia_correo' => null,

                ]);

                $curso=null;
                $curso =  Curso::where('nombre', '=', trim($encuesta->curso))->first();
                if (!$curso) {
                    $curso = Curso::create([
                        'id_centro' =>  $encuesta->id_centro,
                        'id_tipo' => 5535, //Tipo curso
                        'id_categoria' => $encuesta->id_sector,
                        'id_subcategoria' => $encuesta->id_categoria,
                        'id_modalidad' => 5546,
                        'id_modo' => 5550,
                        'nombre' => $encuesta->curso,
                        'descripcion' => $encuesta->curso,
                        'competencias_adquiridas' => $encuesta->curso,
                        'id_pais' => 1438,
                        'id_departamento' => $encuesta->id_departamento,
                        'id_municipio' => $encuesta->id_municipio,
                        'direccion' => $encuesta->direccion,
                        'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $encuesta->fencuesta),
                        'fecha_fin' => Carbon::createFromFormat('d/m/Y', $encuesta->fencuesta),
                        'fecha_fin_matricula' => Carbon::createFromFormat('d/m/Y', $encuesta->fencuesta),
                        'id_unidad_duracion' => 5523, //horas
                        'duracion' => 120,
                        'id_estado' => 5558,
                        'certificado' => true,
                        'costo' => 0.00,
                        'cupos' => 20
                    ]);
                }


                CursosMatricula::create([
                    'id_curso' => $curso->id,
                    'id_participante' => $participante->id,
                    'nombres_participante' => $participante->nombres,
                    'apellidos_participante' => $participante->apellidos,
                    'correo' => $participante->correo,
                    'telefono' => $participante->telefono,
                    'id_tipo_identificacion' => 5496,// cedula por defecto,
                    'documento_identidad' => $participante->documento_identidad,
                    'edad' => $participante->fecha_nacimiento->diff(Carbon::now())->y,
                    'id_estado_civil' => $participante->id_estado_civil,
                    'sexo' => $participante->sexo,
                    'id_pais' => $participante->id_pais,
                    'id_departamento' => $participante->id_departamento,
                    'id_municipio' => $participante->id_ciudad,
                    'direccion' => $participante->direccion,
                    'id_nivel_academico' => $participante->id_nivel_educacion,
                    'estudiando' => $participante->estudiando,
                    'curso_estudiando' => $participante->curso_estudiando,
                    'trabajando' => $participante->trabajando,
                    'lugar_trabajo' => $participante->lugar_trabajo,
                    'salario' => $participante->salario,
                    'referencia_nombre' => $participante->referencia_nombre,
                    'id_parentezco' => $participante->id_parentezco,
                    'referencia_cedula' => $participante->referencia_cedula,
                    'referencia_telefono' => $participante->referencia_telefono,
                    'referencia_correo' => $participante->referencia_correo,
                    'calificacion' => 0,
                    'fecha_nacmiento' =>  $participante->fecha_nacimiento
                    
                ]);

             
              

                $formulario_respuesta = FormulariosRespuesta::create([
                    'id_formulario' => 1, //este es formulario creado para importar estos datos
                    'id_centro' => $encuesta->id_centro,
                    'id_curso' => $curso->id,
                    'id_participante' => $participante->id,
                    'id_evaluador' => null,
                    'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $encuesta->fencuesta),
                    'fecha_fin' => Carbon::createFromFormat('d/m/Y', $encuesta->fencuesta),
                    'nota' => 0
                ]);
           

                $respuestas_campos = DB::select(DB::raw("select * from (select identidad, curso, texto, valor, 
        case 
            when texto='n_participante' then 301 
            when texto='n_cedula' then 302
            when texto= 'f_nacim' then 303
            when texto='edad' then 304
            when texto= 'est_civil' then 305	
            when texto= 'sexo' then 306
            when texto= 'tel_partic' then 307
            when texto= 'direccion' then 308
            when texto= 'depto' then 309
            when texto= 'municipio' then 310
            when texto= 'persona_adic' then 311
            when texto = 'parentesco' then 312
            when texto = 'tel_per2' then 313
            when texto = 'curso' then 314
            when texto = 'centro' then 315
            when texto = 'calidad' then 316
            when texto = 'grado' then 317
            when texto = 'nivel' then 318
            when texto = 'trabaja' then 319
            when texto = 'lugar_trabajo' then 320
            when texto = 'nombre' then 321
            when texto = 'tel_trab' then 322
            when texto = 'direcc_trabajo' then 323
            when texto = 'activ_econ' then 324
            when texto = 'cargo_trab' then  325
            when texto = 'cargo_relcurso' then 326
            when texto =  'ingreso_mes' then  327
            when texto =  'antig_trab' then 328
            when texto = 'trab_dia/sem' then  329
            when texto = 'trab_hr/dia' then 330
            when texto = 'motivo_curso_ntrab' then 331
            when texto = 'motivo_curso_trab' then 334
            when texto = 'no_trab_motivo_curso' then 333
            when texto =  'NPS' then 335
            else null 
         end as  id_campo
         from view_encuestas) as A
         where id_campo is not null and identidad = '" . str_replace("'", "\'", $encuesta->n_participante)."'"));

                foreach ($respuestas_campos as $key => $respuesta) {
                    FormulariosRespuestasCampo::create([
                    'id_formulario_respuesta' => $formulario_respuesta->id,
                    'id_formulario_campo' => $respuesta->id_campo,
                    'valor' => $respuesta->id_campo == 312 ? $parentesco->nombre : $respuesta->valor
                ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public static function importarDatos2()
    {
        $detalle_encuestas = DB::select(DB::raw('select * from view_encuestas2'));
        $encuestas =  DB::select(DB::raw("select  (select id from centros where nombre = centro  collate 'utf8mb4_unicode_ci') as id_centro, 
        case when  
                (select id from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(cursosFP2018.dpto)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select id from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(cursosFP2018.dpto)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select id_departamento from centros where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(cursosFP2018.centro)  limit 1) end as id_departamento,
                
                
                 case 
                    when  
                (select nombre from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(cursosFP2018.dpto)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select nombre from catalogos_detalles where id_padre = 1438 and  catalogos_detalles.nombre  = trim(cursosFP2018.dpto)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select dpt.nombre from centros inner join catalogos_detalles as dpt on centros.id_departamento = dpt.id where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(cursosFP2018.centro)  limit 1) 
                end as departamento,
                
                
                case when  
                (select id from catalogos_detalles where  catalogos_detalles.nombre  = trim(cursosFP2018.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select id from catalogos_detalles where  catalogos_detalles.nombre  = trim(cursosFP2018.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select id_municipio from centros where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(cursosFP2018.centro)  limit 1) end as id_municipio,
                
                 case when  
                (select nombre from catalogos_detalles where  catalogos_detalles.nombre  = trim(cursosFP2018.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1) is not null then
                (select nombre from catalogos_detalles where  catalogos_detalles.nombre  = trim(cursosFP2018.municipio)  COLLATE 'utf8mb4_general_ci'  limit 1)
                else
                (select mun.nombre from centros inner join catalogos_detalles mun on centros.id_municipio = mun.id where centros.nombre COLLATE 'utf8mb4_general_ci' = trim(cursosFP2018.centro)  limit 1) end as municipio,
                
                
         SUBSTRING_INDEX(trim(n_participante),' ',case when  LENGTH(n_participante) - LENGTH(REPLACE(n_participante, ' ', '')) =1 then 1 else  LENGTH(n_participante) - LENGTH(REPLACE(n_participante, ' ', ''))-1 end  ) as nombres,
                SUBSTRING_INDEX(trim(n_participante), ' ', case when LENGTH(n_participante) - LENGTH(REPLACE(n_participante, ' ', '')) =1 then -1 else -2 end) as apellidos,n_participante,
                n_cedula as cedula,f_nacim fecha_nacimiento,edad,est_civil estado_civil,sexo,tel_partic as telefono,
                cursosFP2018.direccion,municipio as muni,dpto,tel_per2 as tel_adicional,
                n_academico,trabaja,cursosFP2018.nombre as lugar_trabajo,ingreso_mes,cursosFP2018.centro, cursosFP2018.curso,activ_econ,rubro_econom,(select id from catalogos_detalles where id_catalogo=34 and activ_econ =catalogos_detalles.nombre COLLATE 'utf8mb4_general_ci') as id_sector,
                (select id from catalogos_detalles where id_catalogo=35 and rubro_econom =catalogos_detalles.nombre COLLATE 'utf8mb4_general_ci') as id_categoria,
                concat(SUBSTRING_INDEX(trim(f_fin_curso), '/', 2),'/', case when  SUBSTRING_INDEX(trim(f_inicio_curso), '/', -1) <20 then concat(20, SUBSTRING_INDEX(trim(f_inicio_curso), '/', -1)) else SUBSTRING_INDEX(trim(f_inicio_curso), '/', -1) end)  as f_inicio_curso,
                concat(SUBSTRING_INDEX(trim(f_fin_curso), '/', 2),'/', case when  SUBSTRING_INDEX(trim(f_fin_curso), '/', -1) <20 then concat(20, SUBSTRING_INDEX(trim(f_fin_curso), '/', -1)) else SUBSTRING_INDEX(trim(f_fin_curso), '/', -1) end)  as f_fin_curso
                
                from cursosFP2018
        "));

        $estados_civil = [];
        array_push($estados_civil, ['id' => 5540, 'estado' => 'Soltero']);
        array_push($estados_civil, ['id' => 5541, 'estado' => 'Casado']);
        array_push($estados_civil, ['id' => 5542, 'estado' => 'Unión de hecho estable']);
        array_push($estados_civil, ['id' => 5540, 'estado' => 'no responde']);

        $nivel_educacion = [];
        array_push($nivel_educacion, ['id' => 5489, 'nivel' => 'Primaria']);
        array_push($nivel_educacion, ['id' => 5491, 'nivel' => 'Secundaria']);
        array_push($nivel_educacion, ['id' => 5493, 'nivel' => 'Técnico']);
        array_push($nivel_educacion, ['id' => 5495, 'nivel' => 'Universidad']);
        array_push($nivel_educacion, ['id' => 5489, 'nivel' => 'No responde']);


        try {
            DB::beginTransaction();
            foreach ($encuestas as $key => $encuesta) {
                //$campos =DB::select(DB::raw("select * from view_encuestas where identidad ='$identidad'"));


                $id_estado_civil = null;
                foreach ($estados_civil as $item) {
                    if ($item['estado'] == $encuesta->estado_civil) {
                        $id_estado_civil = $item['id'];
                        break;
                    }
                }

                if (!$id_estado_civil) {
                    $id_estado_civil = 5540;
                }

                $id_nivel_educacion = null;
                foreach ($nivel_educacion as $item) {
                    if ($item['nivel'] == $encuesta->n_academico) {
                        $id_nivel_educacion = $item['id'];
                        break;
                    }
                }

                $id_parentesco = null;
                // switch ($encuesta->parentesco) {
                //     case "mama":
                //     case "MADRES":
                //     case "madre":
                //     case "hermana":
                //     case "prima":
                //     case "Hermano":

                //     case "mi madre":
                //     case "PADRE":
                //     case "papá":
                //     case "padres":
                //     case "a mi papa":
                //         $id_parentesco = 5554;
                //         break;
                //     case "hemano":
                //     case "hermana":
                //         $id_parentesco = 5555;
                //         break;
                //     case "familiar":
                //     case "tia":
                //     case "abuela":
                //     case "abuelo":
                //     case "abuelita":
                //     case "sobrina":
                //     case "sobrino":
                //     case "prima":
                //     case "primo":
                //     case "Primo":
                //     case "hija":
                //     case "HIJO":
                //     case "PADRASTRO":
                //     case "Suegro":
                //     case "YERNO":


                //         $id_parentesco = 5556;
                //         break;

                //     case "compañera de vida":
                //     case "esposa":
                //     case "Esposo":
                //     case "Esposo.":
                //     case "conyugue":
                //     case "casado":

                //         $id_parentesco = 5570;
                //         break;

                //     case "":
                //     case " ":
                //     case "amigo":
                //     case "amiga":
                //     case "CONOCIDO":
                //     case "Compañero":
                //     case "JEFE":
                //     case "JEFA":
                //     case "TUTOR":
                //     case "Maria jose galeano Salcedo":
                //     case "moreno":
                //     case "COMPAÑERO DE TRABAJO":
                //     case "nada":
                //     case "otro":
                //     case "tutora":
                //         $id_parentesco = 5557;
                //         break;

                      
                // }

                try {
                    $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $encuesta->fecha_nacimiento);
                } catch (\Throwable $th) {
                    $fecha_nacimiento =Carbon::now();
                }

                $parentesco = CatalogosDetalle::find($id_parentesco);

                if (!$parentesco) {
                    $parentesco =  CatalogosDetalle::find(5556);
                }

               
                $participante = Participante::create([
                    'nombres' => $encuesta->nombres,
                    'apellidos' => $encuesta->apellidos,
                    'telefono' => $encuesta->telefono,
                    'correo' => null,
                    'id_tipo_identificacion' => 5496,
                    'documento_identidad' => $encuesta->cedula,
                    'menor_edad' => ((int) $encuesta->edad < 16),
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'id_estado_civil' => $id_estado_civil,
                    'sexo' => $encuesta->sexo,
                    'id_pais' => 1438,
                    'id_departamento' => $encuesta->id_departamento,
                    'id_ciudad' => $encuesta->id_municipio,
                    'direccion' => $encuesta->direccion,
                    'id_nivel_educacion' => $id_nivel_educacion,
                    'estudiando' => false,
                    'curso_estudiando' => null,
                    'trabajando' => strtolower($encuesta->trabaja) == "si",
                    'lugar_trabajo' => $encuesta->lugar_trabajo,
                    'salario' => str_replace(",", "", $encuesta->ingreso_mes) == "" ? 0 :  (int)str_replace(",", "", $encuesta->ingreso_mes),
                    'referencia_nombre' =>  null,
                    'id_parentezco' => $id_parentesco,
                    'referencia_cedula' => null,
                    'referencia_telefono' => $encuesta->tel_adicional,
                    'referencia_correo' => null,

                ]);

                $curso=null;
                $curso =  Curso::where('nombre', '=', trim($encuesta->curso))->first();
                if (!$curso) {
                    $curso = Curso::create([
                        'id_centro' =>  $encuesta->id_centro,
                        'id_tipo' => 5535, //Tipo curso
                        'id_categoria' => $encuesta->id_sector,
                        'id_subcategoria' => $encuesta->id_categoria,
                        'id_modalidad' => 5546,
                        'id_modo' => 5550,
                        'nombre' => $encuesta->curso,
                        'descripcion' => $encuesta->curso,
                        'competencias_adquiridas' => $encuesta->curso,
                        'id_pais' => 1438,
                        'id_departamento' => $encuesta->id_departamento,
                        'id_municipio' => $encuesta->id_municipio,
                        'direccion' => $encuesta->direccion,
                        'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $encuesta->f_inicio_curso),
                        'fecha_fin' => Carbon::createFromFormat('d/m/Y', $encuesta->f_fin_curso),
                        'fecha_fin_matricula' => Carbon::createFromFormat('d/m/Y', $encuesta->f_fin_curso),
                        'id_unidad_duracion' => 5523, //horas
                        'duracion' => 120,
                        'id_estado' => 5558,
                        'certificado' => true,
                        'costo' => 0.00,
                        'cupos' => 20
                    ]);
                }


                CursosMatricula::create([
                    'id_curso' => $curso->id,
                    'id_participante' => $participante->id,
                    'nombres_participante' => $participante->nombres,
                    'apellidos_participante' => $participante->apellidos,
                    'correo' => $participante->correo,
                    'telefono' => $participante->telefono,
                    'id_tipo_identificacion' => 5496,// cedula por defecto,
                    'documento_identidad' => $participante->documento_identidad,
                    'edad' => $participante->fecha_nacimiento->diff(Carbon::now())->y,
                    'id_estado_civil' => $participante->id_estado_civil,
                    'sexo' => $participante->sexo,
                    'id_pais' => $participante->id_pais,
                    'id_departamento' => $participante->id_departamento,
                    'id_municipio' => $participante->id_ciudad,
                    'direccion' => $participante->direccion,
                    'id_nivel_academico' => $participante->id_nivel_educacion,
                    'estudiando' => $participante->estudiando,
                    'curso_estudiando' => $participante->curso_estudiando,
                    'trabajando' => $participante->trabajando,
                    'lugar_trabajo' => $participante->lugar_trabajo,
                    'salario' => $participante->salario,
                    'referencia_nombre' => $participante->referencia_nombre,
                    'id_parentezco' => $participante->id_parentezco,
                    'referencia_cedula' => $participante->referencia_cedula,
                    'referencia_telefono' => $participante->referencia_telefono,
                    'referencia_correo' => $participante->referencia_correo,
                    'calificacion' => 0,
                    'fecha_nacmiento' =>  $participante->fecha_nacimiento
                    
                ]);

             
              

                $formulario_respuesta = FormulariosRespuesta::create([
                    'id_formulario' => 5, //este es formulario creado para importar estos datos
                    'id_centro' => $encuesta->id_centro,
                    'id_curso' => $curso->id,
                    'id_participante' => $participante->id,
                    'id_evaluador' => null,
                    'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $encuesta->f_fin_curso),
                    'fecha_fin' => Carbon::createFromFormat('d/m/Y', $encuesta->f_fin_curso),
                    'nota' => 0
                ]);
           

                $respuestas_campos = DB::select(DB::raw("select * from (select identidad, curso, texto, valor, 
            case 
                when texto='n_participante' then 406 
                when texto='n_cedula' then 407
                when texto= 'f_nacim' then 408
                when texto='edad' then 409
                when texto= 'est_civil' then 410	
                when texto= 'sexo' then 411
                when texto= 'tel_partic' then 412
                when texto= 'direccion' then 413
                when texto= 'depto' then 414
                when texto= 'municipio' then 415
                when texto= 'persona_adic' then 416
                when texto = 'parentesco' then 417
                when texto = 'tel_per2' then 418
                when texto = 'curso' then 419
                when texto = 'centro' then 420
                when texto = 'calidad' then 421
                when texto = 'grado' then 423
                when texto = 'nivel' then 422
                when texto = 'trabaja' then 424
                when texto = 'lugar_trabajo' then 425
                when texto = 'nombre' then 426
                when texto = 'tel_trab' then 427
                when texto = 'direcc_trabajo' then 428
                when texto = 'activ_econ' then 429
                when texto = 'cargo_trab' then  430
                when texto = 'cargo_relcurso' then 431
                when texto =  'ingreso_mes' then  432
                when texto =  'antig_trab' then 433
                when texto = 'trab_dia/sem' then  434
                when texto = 'trab_hr/dia' then 435
                when texto = 'motivo_curso_ntrab' then 436
                when texto = 'motivo_curso_trab' then 437
                when texto = 'no_trab_motivo_curso' then 438
                when texto =  'NPS' then 440
                else null 
             end as  id_campo
             from view_encuestas2) as A
             where id_campo is not null and identidad = '" . str_replace("'", "\'", $encuesta->n_participante)."'"));

                foreach ($respuestas_campos as $key => $respuesta) {
                    FormulariosRespuestasCampo::create([
                    'id_formulario_respuesta' => $formulario_respuesta->id,
                    'id_formulario_campo' => $respuesta->id_campo,
                    'valor' => $respuesta->id_campo == 312 ? $parentesco->nombre : $respuesta->valor
                ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
