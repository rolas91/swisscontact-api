<?php

namespace  App\Functions;

use App\Models\Curso;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use Illuminate\Support\Facades\DB;

class CursosDAL
{
    public $usuario;
    public $filtro;


    public function __construct($usuario, $filtro)
    {
        $this->usuario = $usuario;
        $this->filtro = $filtro;
    }

    public function getAllCursos()
    {
        $usuario = $this->usuario;
        $filtro = $this->filtro;
        $id_centros = UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro');
        
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
            ->leftJoin('cursos_matriculas as matriculas','cursos.id','matriculas.id_curso')
            ->leftJoin('participantes','matriculas.id_participante','participantes.id')

            ->where([
                ['cursos.deleted_at', '=', null],
                
            ])
            ->whereIn('catalogo_cursos.id_centro', $id_centros)
            ->whereRaw("catalogo_cursos.nombre like '%$this->filtro%'", [])
            // ->whereNotIn('cursos.id',$cursos_excluidos)
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
					cast((cast(cursos.costo as unsigned) * (select count(1) from cursos_matriculas where cursos_matriculas.id_curso=cursos.id)) as unsigned) as costo_curso,
					cursos.cupos,
					centro.nombre as centro,
					departamento.nombre as departamento,
					estado.nombre as estado,
					modalidad.nombre as modalidad,
					modo.nombre as modo,
					municipio.nombre as municipio,
					pais.nombre as pais,
					sector.nombre as sector,
					tipo.nombre as tipo,
					unidad_duracion.nombre as unidad_duracion,
                    (select count(1) from cursos_matriculas where cursos_matriculas.id_curso=cursos.id) as cantidad_participantes,
                    participantes.sexo,
                    TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) AS edad,
                    case when TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) between 12 and 21 then '12-21'
	 when TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) between 22 and 31 then  '22-31'
	 when TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) between 32 and 41 then  '32-41'
	 when TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) between 42 and 51 then  '42-51'
	 when TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) between 52 and 61 then  '52-61'
	 when TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) between 62 and 71 then  '62-71'
     else '>71' end as rango_edad,
     participantes.salario,
     (select nombre from catalogos_detalles where id = participantes.id_estado_civil) as estado_civil,
     (select nombre from catalogos_detalles where id = participantes.id_nivel_educacion) as nivel_educacion,
     participantes.estudiando,
     participantes.trabajando,
     cursos.fecha_inicio,
     cursos.fecha_fin
					", [])
                    ->orderBy('cursos.id', 'desc')
            //->toSql();
            ->get();
        return $cursos;
    }
}
