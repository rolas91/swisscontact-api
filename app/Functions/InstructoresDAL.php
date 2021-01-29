<?php

namespace  App\Functions;

use App\Models\Instructore;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use Illuminate\Support\Facades\DB;

class InstructoresDAL
{
    private $user,$filtro;
    

    public function __construct( $user,$filtro) {
        $this->user = $user;
        $this->filtro = $filtro;
    }


    public  function getAllInstructores()
    {

        $usuario = $this->user;
		$id_centros = UsuariosCentro::where('id_usuario',$usuario->id)->pluck('id_centro');
		$ids_usuarios = UsuariosCentro::whereIn('id_centro',$id_centros)->pluck('id_usuario');
        $instructores = Instructore::
			join('catalogos_detalles as departamento', 'instructores.id_departamento', 'departamento.id')
			->join('catalogos_detalles as municipio', 'instructores.id_municipio', 'municipio.id')
			->join('catalogos_detalles as nivel_academico', 'instructores.id_nivel_academico', 'nivel_academico.id')
			->join('catalogos_detalles as pais', 'instructores.id_pais', 'pais.id')
			->join('catalogos_detalles as tipo_identificacion', 'instructores.id_tipo_identificacion', 'tipo_identificacion.id')
			->join('usuarios', 'instructores.id_usuario', 'usuarios.id')
			->whereIn('instructores.id_usuario',$ids_usuarios)
			->whereRaw("concat(instructores.nombres,' ',instructores.apellidos) like '%$this->filtro%'", [])
			->selectRaw("
					instructores.nombres,
					instructores.apellidos,
					instructores.telefono_1,
					instructores.telefono_2,
					instructores.telefono_otro,
					instructores.sexo,
					instructores.direccion,
					DATE_FORMAT(instructores.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
					instructores.id_tipo_identificacion,
					instructores.documento_identidad,
					instructores.anios_experiencia,
					instructores.ocupacion,
					instructores.especialidad,
					instructores.calificacion,
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
					where uc.id_usuario = instructores.id_usuario) as centros_asignados,
					(
        				SELECT  COUNT(*)
        				FROM    instructores par
						WHERE   par.nombres = instructores.nombres
						AND par.apellidos = instructores.apellidos
        			) as contador
					
					", [])
            ->get();
            return $instructores;
    }
}