<?php

namespace App\Functions;

use Exception;
use Carbon\Carbon;
use App\Models\Curso;
use App\Models\Centro;
use App\Models\Formulario;
use App\Models\Participante;
use App\Models\CatalogoCurso;
use App\Models\CursosMatricula;
use App\Models\CatalogosDetalle;
use App\Models\FormulariosCampo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\FormulariosRespuesta;
use App\Models\FormulariosRespuestasCampo;

class DataImport
{
    public static function importar()
    {
        try {
            DB::statement("delete from formularios_respuestas_campos;");
            DB::statement("delete from formularios_respuestas;");
            DB::statement("delete from cursos_matriculas;");
            DB::statement("delete from cursos;");
            DB::statement("delete from catalogo_cursos;");
            DB::statement("delete from participantes;");

            
            DB::beginTransaction();
            //Insertamos en la tabla catalogo_cursos;

            DB::statement("
            insert into catalogo_cursos (
            id_tipo,
            id_centro,
            id_sector,
            id_unidad_duracion,
            nombre,
            descripcion,
            competencias_adquiridas,
            duracion,
            created_at)
            select distinct 5535 as id_tipo ,
            (select id from centros as c where c.nombre = b.nombre_del_centro) as id_centro,
            (select id from catalogos_detalles where id_catalogo = 34 and catalogos_detalles.nombre = b.sector) as id_sector,
            5523 as id_unidad_duracion,
            b.nombre_del_curso,
            b.nombre_del_curso,
            '' as competencias_adquiridas,
            ifnull((select carga_horaria from base_datos_cursos as bd where b.nombre_del_curso = bd.nombre_del_curso and b.sector = bd.sector  limit 1),0),
            now()
            from base_datos_cursos as b;
            ");

            //Insertamos en la tabla cursos
            $base_cursos = DB::select('select * from base_datos_cursos as b;');
            foreach ($base_cursos as $key => $curso) {
                $id_centro = Centro::withTrashed()->where('nombre', $curso->Nombre_del_centro)->value('id');
                $id_catalogo_curso = CatalogoCurso::where('nombre', $curso->Nombre_del_curso)
                    ->where('id_centro', $id_centro)->Value('id');
                

              
                $catalogo_curso = CatalogoCurso::with('centro')->findOrFail($id_catalogo_curso);

                $nombre_centro = self::acronym($catalogo_curso->centro->nombre);
                $consecutivo = Curso::where('id_curso', $id_catalogo_curso)->count() + 1;
                $nombre_curso = self::acronym($catalogo_curso->nombre);

                $centro = Centro::findOrFail($catalogo_curso->id_centro);
                $codigo = strtoupper($nombre_centro . '-' . $nombre_curso . '-' . $consecutivo);
                $direccion = $centro->direccion;

                $fecha_inicio_default = Carbon::createFromFormat('m/d/Y', '01/01/2019');
                $fecha_fin_default = Carbon::createFromFormat('m/d/Y', '03/01/2019');

                $curso = Curso::create([
                    'id_curso' => $id_catalogo_curso,
                    'id_modalidad' => 5546,
                    'id_modo' => 5550,
                    'codigo' => "$codigo",
                    'id_pais' => $centro->id_pais,
                    'id_departamento' => $centro->id_departamento,
                    'id_municipio' => $centro->id_municipio,
                    'direccion' => $direccion,
                    'fecha_inicio' => $curso->inicio ? Carbon::createFromFormat('m/d/Y', $curso->inicio) : $fecha_inicio_default,
                    'fecha_fin' => $curso->fin ? Carbon::createFromFormat('m/d/Y', $curso->fin) : $fecha_fin_default,
                    'id_estado' => 5558,
                    'costo' => $curso->costo_por_participante_USD ? $curso->costo_por_participante_USD : 0,
                    'cupos' => $curso->total ? $curso->total : 0,
                    'fecha_fin_matricula' => $curso->fin ? Carbon::createFromFormat('m/d/Y', $curso->fin) : $fecha_fin_default,
                    'certificado' => true,
                    'created_at' => Carbon::now(),
                ]);
            }


            $datos1 = DB::select('select *, CASE WHEN
            STR_TO_DATE(fecha_nacimiento, "%m/%d/%Y" ) is null THEN
            STR_TO_DATE(fecha_nacimiento, "%d/%m/%Y") ELSE
            STR_TO_DATE(fecha_nacimiento, "%m/%d/%Y") END as f_nacimiento,
             CASE WHEN
            STR_TO_DATE(fecha_encuesta, "%m/%d/%Y" ) is null THEN
            STR_TO_DATE(fecha_encuesta, "%d/%m/%Y") ELSE
            STR_TO_DATE(fecha_encuesta, "%m/%d/%Y") END as f_encuesta,

               CASE WHEN
            STR_TO_DATE(inicio, "%m/%d/%Y" ) is null THEN
            STR_TO_DATE(inicio, "%d/%m/%Y") ELSE
            STR_TO_DATE(inicio, "%m/%d/%Y") END as f_inicio
            from base_datos_consolidada_2018_2019 ');

        
            $formulario1 = Formulario::find(30);
            $formulario_campos1 = FormulariosCampo::where('id_formulario', $formulario1->id)->get();


            foreach ($datos1 as $key => $row) {
                self::importarDatos($row, $formulario1, $formulario_campos1);
            }

          

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public static function acronym($string)
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

    // uses regex that accepts any word character or hyphen in last name
    public static function split_name($name)
    {
        $name = trim($name);
        $arr = explode(" ", $name);
        $counter = count($arr);
        $first_name = '';
        $last_name = '';

        //4 first_name 2 last_name
        if ($counter === 6) {
            $first_name = $arr[0] . ' ' . $arr[1] . ' ' . $arr[2] . ' ' . $arr[3];
            $last_name = $arr[4] . ' ' . $arr[5];
        }

        //3 first_name 2 last_name
        if ($counter === 5) {
            $first_name = $arr[0] . ' ' . $arr[1] . ' ' . $arr[2];
            $last_name = $arr[3] . ' ' . $arr[4];
        }

        //2 first_name 2 last_name
        if ($counter === 4) {
            $first_name = $arr[0] . ' ' . $arr[1];
            $last_name = $arr[2] . ' ' . $arr[3];
        }

        //1 first_name 2 last_name
        if ($counter === 3) {
            $first_name = $arr[0];
            $last_name = $arr[1] . ' ' . $arr[2];
        }

        //1 first_name 1 last_name
        if ($counter === 2) {
            $first_name = $arr[0];
            $last_name = $arr[1];
        }

        if ($counter === 1) {
            $first_name = $arr[0];
            $last_name = '';
        }

        return array($first_name, $last_name);
    }

    public static function importarDatos($row, $formulario, $formulario_campos)
    {
        //validamos si ya existe el participante lo omitimos
        $participante = Participante::
            whereRaw("concat(nombres, ' ', apellidos) = '?'", [$row->n_participante])->first();
        if ($participante) {
            return;
        }

        $now = Carbon::now();
        $fecha_nacimiento = Carbon::parse($row->f_nacimiento);
        $edad = Carbon::parse($row->f_nacimiento)->diffInYears($now);
        $id_estado_civil = CatalogosDetalle::where([
            ['id_catalogo', 40],
            ['nombre', $row->est_civil],
        ])->value('id');

        $centro = Centro::where('nombre', $row->centro)->firstOrFail();
        $id_departamtento = CatalogosDetalle::where([
            ['id_catalogo', 30],
            ['nombre', $row->depto],
        ])->value('id');

        $id_municipio = CatalogosDetalle::where([
            ['id_catalogo', 31],
            ['nombre', $row->municipio],
        ])->value('id');

        if (!$id_departamtento) {
            $id_departamtento = $centro->id_departamento;
        }

        if (!$id_municipio) {
            $id_municipio = $centro->id_municipio;
        }

        $id_nivel_educacion = CatalogosDetalle::where([
            ['id_catalogo', 32],
            ['nombre', $row->nivel],
        ])->value('id');

        $trabajando = strtolower($row->trabaja) === 'si';
        $salario = $row->ingreso_mes ? (int) str_replace(',', '', '9,900.00') : 0;

        $id_parentesco = CatalogosDetalle::where([
            ['id_catalogo', 43],
            ['nombre', $row->parentesco],
        ])->value('id');

        $participante = Participante::create([
            'nombres' => self::split_name($row->n_participante)[0],
            'apellidos' => self::split_name($row->n_participante)[1],
            'telefono' => $row->tel_partic,
            'correo' => null,
            'id_tipo_identificacion' => 5496, //cedula
            'documento_identidad' => $row->cedula,
            'menor_edad' => $edad >= 18,
            'fecha_nacimiento' => $fecha_nacimiento,
            'id_estado_civil' => $id_estado_civil,
            'sexo' => $row->sexo,
            'id_pais' => 1438,
            'id_departamento' => $id_departamtento,
            'id_ciudad' => $id_municipio,
            'direccion' => $row->direccion,
            'id_nivel_educacion' => $id_nivel_educacion,
            'estudiando' => false,
            'curso_estudiando' => null,
            'trabajando' => $trabajando,
            'lugar_trabajo' => $row->nombre,
            'salario' => $salario,
            'referencia_nombre' => $row->persona_adic,
            'id_parentezco' => $id_parentesco,
            'referencia_cedula' => null,
            'referencia_telefono' => $row->tel_per2,
            'referencia_correo' => null,
        ]);

        if (!$participante) {
            throw new Exception("El participante es nulo");
        }

        $catalogo_curso = CatalogoCurso::where('id_centro', $centro->id)
        ->where('nombre', $row->curso)
        ->first();


        $id_catalogo_curso = $catalogo_curso->id;
        $fecha_inicio_default = Carbon::createFromFormat('m/d/Y', '01/01/2019');
        $inicio = $row->inicio ? Carbon::createFromFormat('m/d/Y', $row->inicio) : $fecha_inicio_default;

        $curso = DB::select("
        SELECT * 
        FROM cursos
        where id_curso = $id_catalogo_curso
        ORDER BY ABS( DATEDIFF( fecha_inicio, '$inicio' ) ) 
        LIMIT 1;
        ")[0];

    
        CursosMatricula::create([
            'id_curso' => $curso->id,
            'id_participante' => $participante->id,
            'nombres_participante' => $participante->nombres,
            'apellidos_participante' => $participante->apellidos,
            'telefono' => $participante->telefono,
            'correo' => $participante->correo,
            'id_tipo_identificacion' => 5496, //cedula
            'documento_identidad' => $participante->documento_identidad,
            'edad' => $edad,
            'id_estado_civil' => $id_estado_civil,
            'sexo' => $participante->sexo,
            'id_pais' => $participante->id_pais,
            'id_departamento' => $participante->id_departamento,
            'id_municipio' => $participante->id_ciudad,
            'direccion' => $participante->direccion,
            'id_nivel_academico' => $participante->id_nivel_educacion,
            'estudiando' => false,
            'curso_estudiando' => null,
            'trabajando' => $trabajando,
            'lugar_trabajo' => $row->nombre,
            'salario' => $salario,
            'referencia_nombre' => $row->persona_adic,
            'id_parentezco' => $id_parentesco,
            'referencia_cedula' => null,
            'referencia_telefono' => $row->tel_per2,
            'referencia_correo' => null,
            'calificacion' => 0,
            'fecha_nacimiento' => $fecha_nacimiento,
        ]);

        $formulario_respuesta = FormulariosRespuesta::create([
            'id_formulario' => $formulario->id,
            'id_participante' => $participante->id,
            'id_evaluador' => null,
            'fecha_inicio' => $row->f_encuesta,
            'fecha_fin' => $row->f_encuesta,
            'duracion' => 0,
            'nota' => 0,
            'id_centro' => $centro->id,
            'id_curso' => $curso->id,
            'nombre_participante' => null,
            'correo_participante' => null,
        ]);

        foreach ($formulario_campos as $key => $campo) {
            $valor = '';
            switch ($campo->texto) {
                case 'Cargo que desempeña':
                    $valor = $row->cargo_trab;
                    break;
                case 'Último grado de educación alcanzado':
                    $valor = $row->nivel;
                    break;
                case 'Escriba el grado':
                    $valor = $row->grado;
                    break;
                case 'Como califica la calidad del curso':
                    $valor = $row->calidad;
                    break;
                case '¿Trabaja actualmente?':
                    $valor = $row->trabaja;
                    break;
                case '¿Dónde trabaja?':
                    $valor = $row->lugar_trabajo;
                    break;
                case 'Nombre de la empresa o empleador':
                    $valor = $row->nombre;
                    break;
                case 'Teléfono empresa':
                    $valor = $row->tel_trab;
                    break;
                case 'Dirección empresa':
                    $valor = $row->direcc_trabajo;
                    break;
                case 'Selecciona la actividad económica principal de la empresa o de su trabajo por cuenta propia':
                    $valor = $row->activ_econ;
                    break;
                case 'Cargo que desempeña':
                    $valor = $row->cargo_trab;
                    break;
                case 'El cargo se relaciona con el tema del curso':
                    $valor = $row->cargo_relcurso;
                    break;
                case 'Departamento':
                    $valor = $row->depto;
                    break;
                case 'Municipio':
                    $valor = $row->municipio;
                    break;

                case '¿Cuánto gana al mes (en Córdobas)?':
                    $valor = $row->ingreso_mes;
                    break;

                case '¿Cuántos años tiene de estar en ese trabajo?':
                    $valor = $row->antig_trab;
                    break;

                case '¿Cuántos días a la semana trabaja?':
                    $valor = $row->trab_diasem;
                    break;

                case '¿Cuántas horas al día trabaja?':
                    $valor = $row->trab_hrdia;
                    break;

                case '¿Que lo motivó a participar en este curso?':
                    $valor = $row->motivacion_trabaja;
                    break;

                case 'Otro Especifique (en caso de seleccionar otro en la opción anterior)':
                    $valor = '';
                    break;

                case 'Para los que no trabajan, ¿Que lo motivó a participar en este curso?':
                    $valor = $row->motivac_no_trabaja;
                    break;

                case 'Otro (especifique)':
                    $valor = "";
                    break;
                case '¿Qué tan probable es que recomiendes este curso a tus amigos o familiares? , Califique del 1 al 10':
                    $valor = $row->NPS;
                    break;

                default:
                    break;
            }

            $formulario_respuesta_campo = FormulariosRespuestasCampo::create([
                'id_formulario_respuesta' => $formulario_respuesta->id,
                'id_formulario_campo' => $campo->id,
                'valor' => $valor,
                'nota' => 0,
                'evaluada' => 0,
            ]);
        }
    }
}
