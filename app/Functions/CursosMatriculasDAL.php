<?php

namespace  App\Functions;

use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Models\CursosMatricula;
use Illuminate\Support\Facades\DB;

class CursosMatriculasDAL
{
    public $id_centros;
    public $filtro;
    public $filtro_curso;


    public function __construct($id_centros, $filtro,$filtro_curso)
    {
        $this->filtro =  $filtro;
        $this->id_centros  = $id_centros;
        $this->filtro_curso  = $filtro_curso;
    }

    public function getAllInscripciones()
    {

        $filtro = $this->filtro;
        $id_centros = $this->id_centros;
        $filtro_curso = $this->filtro_curso;



        $cursos_matriculas =CursosMatricula::
        join('cursos as cursos', 'cursos_matriculas.id_curso', 'cursos.id')
		->join('catalogo_cursos', 'catalogo_cursos.id', 'cursos.id_curso')
		->join('centros','catalogo_cursos.id_centro','centros.id')
        ->join('catalogos_detalles as departamento', 'cursos_matriculas.id_departamento', 'departamento.id')
        ->join('catalogos_detalles as estado_civil', 'cursos_matriculas.id_estado_civil', 'estado_civil.id')
        ->join('catalogos_detalles as municipio', 'cursos_matriculas.id_municipio', 'municipio.id')
        ->join('catalogos_detalles as nivel_academico', 'cursos_matriculas.id_nivel_academico', 'nivel_academico.id')
        ->join('catalogos_detalles as pais', 'cursos_matriculas.id_pais', 'pais.id')
        ->leftJoin('catalogos_detalles as parentezco', 'cursos_matriculas.id_parentezco', 'parentezco.id')
        ->join('participantes as participante', 'cursos_matriculas.id_participante', 'participante.id')
        ->join('catalogos_detalles as tipo_identificacion', 'cursos_matriculas.id_tipo_identificacion', 'tipo_identificacion.id')
        ->where([
                     ['cursos_matriculas.deleted_at', '=', null]
                 ])
        ->whereRaw("concat(cursos_matriculas.nombres_participante,' ', cursos_matriculas.apellidos_participante)  like '%$filtro%'
		AND case when cursos_matriculas.id_participante is null then true else catalogo_cursos.id_centro in ($id_centros) end  ".$filtro_curso, [])
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
					catalogo_cursos.nombre as curso,
					departamento.nombre as departamento,
					estado_civil.nombre as estado_civil,
					municipio.nombre as municipio,
					nivel_academico.nombre as nivel_academico,
					pais.nombre as pais,
					centros.nombre as centro,
					parentezco.nombre as parentezco,
					concat(participante.nombres,' ', participante.apellidos) as participante,
					tipo_identificacion.nombre as tipo_identificacion,
                    cursos.id_estado, cursos.codigo,
                    cursos_matriculas.egresado,
                    cursos_matriculas.comentarios", []);

        return $cursos_matriculas;
    }
}
