<?php

namespace  App\Functions;

use App\Models\Participante;
use Illuminate\Support\Facades\DB;

class ParticipantesDAL
{
    public static function getAllParticipantes()
    {

        $participantes =Participante::
        join('catalogos_detalles as ciudad', 'participantes.id_ciudad','ciudad.id')
		->join('catalogos_detalles as departamento', 'participantes.id_departamento','departamento.id')
		->join('catalogos_detalles as estado_civil', 'participantes.id_estado_civil','estado_civil.id')
		->join('catalogos_detalles as nivel_educacion', 'participantes.id_nivel_educacion','nivel_educacion.id')
		->join('catalogos_detalles as pais', 'participantes.id_pais','pais.id')
		->join('catalogos_detalles as tipo_identificacion', 'participantes.id_tipo_identificacion','tipo_identificacion.id')
		->join('catalogos_detalles as parentesco', 'participantes.id_parentezco','parentesco.id')
		
        ->selectRaw("
					participantes.foto,
					participantes.nombres,
					participantes.apellidos,
					participantes.telefono,
					participantes.correo,
					participantes.documento_identidad,
					participantes.menor_edad,
					DATE_FORMAT(participantes.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
					TIMESTAMPDIFF(YEAR, participantes.fecha_nacimiento, CURDATE()) AS edad,
					case 
					when participantes.sexo = 'M' then 'Hombre'
					when participantes.sexo = 'F' then 'Mujer'
					else participantes.sexo end as sexo ,
					participantes.direccion,
					participantes.estudiando,
					participantes.curso_estudiando,
					participantes.trabajando,
					participantes.lugar_trabajo,
					participantes.salario,
					participantes.referencia_nombre,
					participantes.referencia_cedula,
					participantes.referencia_telefono,
					participantes.referencia_correo,
					ciudad.nombre as ciudad,
					departamento.nombre as departamento,
					estado_civil.nombre as estado_civil,
					nivel_educacion.nombre as nivel_educacion,
					pais.nombre as pais,
                    tipo_identificacion.nombre as tipo_identificacion,
					parentesco.nombre as parentesco,
					(
        				SELECT  COUNT(*)
        				FROM    participantes par
						WHERE   par.nombres = participantes.nombres
						AND par.apellidos = participantes.apellidos
        			) as contador
                    
                    ",[])
        ->get();
        return $participantes;
    }
}