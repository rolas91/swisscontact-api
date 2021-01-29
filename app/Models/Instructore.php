<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 11 Sep 2019 22:05:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Instructore
 * 
 * @property int $id
 * @property int $id_usuario
 * @property string $nombres
 * @property string $apellidos
 * @property string $telefono_1
 * @property string $telefono_2
 * @property string $telefono_otro
 * @property int $id_pais
 * @property int $id_departamento
 * @property int $id_municipio
 * @property string $sexo
 * @property string $direccion
 * @property \Carbon\Carbon $fecha_nacimiento
 * @property int $id_tipo_identificacion
 * @property string $documento_identidad
 * @property int $anios_experiencia
 * @property string $ocupacion
 * @property string $especialidad
 * @property int $calificacion
 * @property int $id_nivel_academico
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \App\Usuario $usuario
 * @property \Illuminate\Database\Eloquent\Collection $centros
 * @property \Illuminate\Database\Eloquent\Collection $cursos
 *
 * @package App\Models
 */
class Instructore extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_usuario' => 'integer',
		'id_pais' => 'integer',
		'id_departamento' => 'integer',
		'id_municipio' => 'integer',
		'id_tipo_identificacion' => 'integer',
		'anios_experiencia' => 'integer',
		'calificacion' => 'integer',
		'id_nivel_academico' => 'integer'
	];

	// protected $dates = [
	// 	'fecha_nacimiento'
	// ];

	protected $fillable = [
		'id_usuario',
		'nombres',
		'apellidos',
		'telefono_1',
		'telefono_2',
		'telefono_otro',
		'id_pais',
		'id_departamento',
		'id_municipio',
		'sexo',
		'direccion',
		'fecha_nacimiento',
		'id_tipo_identificacion',
		'documento_identidad',
		'anios_experiencia',
		'ocupacion',
		'especialidad',
		'calificacion',
		'id_nivel_academico'
	];

	public function tipo_identificacion()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_tipo_identificacion');
	}

	public function usuario()
	{
		return $this->belongsTo(\App\Usuario::class, 'id_usuario');
	}


	public function cursos()
	{
		return $this->belongsToMany(\App\Models\Curso::class, 'cursos_instructores', 'id_instructor', 'id_curso')
			->withPivot('id')
			->withTimestamps();
	}
}
