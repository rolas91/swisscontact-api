<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 23 Nov 2019 12:22:57 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Curso
 * 
 * @property int $id
 * @property int $id_curso
 * @property int $id_modalidad
 * @property int $id_modo
 * @property string $codigo
 * @property int $id_pais
 * @property int $id_departamento
 * @property int $id_municipio
 * @property string $direccion
 * @property \Carbon\Carbon $fecha_inicio
 * @property \Carbon\Carbon $fecha_fin
 * @property int $id_estado
 * @property float $costo
 * @property int $cupos
 * @property \Carbon\Carbon $fecha_fin_matricula
 * @property bool $certificado
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\CatalogoCurso $catalogo_curso
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \Illuminate\Database\Eloquent\Collection $instructores
 * @property \Illuminate\Database\Eloquent\Collection $cursos_matriculas
 * @property \Illuminate\Database\Eloquent\Collection $formularios_respuestas
 *
 * @package App\Models
 */
class Curso extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_curso' => 'integer',
		'id_modalidad' => 'integer',
		'id_modo' => 'integer',
		'id_pais' => 'integer',
		'id_departamento' => 'integer',
		'id_municipio' => 'integer',
		'id_estado' => 'integer',
		'costo' => 'float',
		'cupos' => 'integer',
		'certificado' => 'bool'
	];

	// protected $dates = [
	// 	'fecha_inicio',
	// 	'fecha_fin',
	// 	'fecha_fin_matricula'
	// ];

	protected $fillable = [
		'id_curso',
		'id_modalidad',
		'id_modo',
		'codigo',
		'id_pais',
		'id_departamento',
		'id_municipio',
		'direccion',
		'fecha_inicio',
		'fecha_fin',
		'id_estado',
		'costo',
		'cupos',
		'fecha_fin_matricula',
		'certificado'
	];

	public function catalogo_curso()
	{
		return $this->belongsTo(\App\Models\CatalogoCurso::class, 'id_curso');
	}

	public function catalogos_detalle()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_pais');
	}

	public function instructores()
	{
		return $this->belongsToMany(\App\Models\Instructore::class, 'cursos_instructores', 'id_curso', 'id_instructor')
			->withPivot('id')
			->withTimestamps();
	}

	public function cursos_matriculas()
	{
		return $this->hasMany(\App\Models\CursosMatricula::class, 'id_curso');
	}

	public function formularios_respuestas()
	{
		return $this->hasMany(\App\Models\FormulariosRespuesta::class, 'id_curso');
	}
}
