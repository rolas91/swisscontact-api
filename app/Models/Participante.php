<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 11 Sep 2019 22:05:49 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Participante
 * 
 * @property int $id
 * @property string $foto
 * @property string $nombres
 * @property string $apellidos
 * @property string $telefono
 * @property int $id_tipo_identificacion
 * @property string $documento_identidad
 * @property bool $menor_edad
 * @property \Carbon\Carbon $fecha_nacimiento
 * @property int $id_estado_civil
 * @property string $sexo
 * @property int $id_pais
 * @property int $id_departamento
 * @property int $id_ciudad
 * @property string $direccion
 * @property int $id_nivel_educacion
 * @property bool $estudiando
 * @property string $curso_estudiando
 * @property bool $trabajando
 * @property string $lugar_trabajo
 * @property float $salario
 * @property string $referencia_nombre
 * @property int $id_parentezco
 * @property string $referencia_cedula
 * @property string $referencia_telefono
 * @property string $referencia_correo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \Illuminate\Database\Eloquent\Collection $cursos_matriculas
 *
 * @package App\Models
 */
class Participante extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_tipo_identificacion' => 'integer',
		'menor_edad' => 'bool',
		'id_estado_civil' => 'integer',
		'id_pais' => 'integer',
		'id_departamento' => 'integer',
		'id_ciudad' => 'integer',
		'id_nivel_educacion' => 'integer',
		'estudiando' => 'bool',
		'trabajando' => 'bool',
		'salario' => 'float',
		'id_parentezco' => 'integer'
	];

	// protected $dates = [
	// 	'fecha_nacimiento'
	// ];

	protected $fillable = [
		'foto',
		'nombres',
		'apellidos',
		'telefono',
		'correo',
		'id_tipo_identificacion',
		'documento_identidad',
		'menor_edad',
		'fecha_nacimiento',
		'id_estado_civil',
		'sexo',
		'id_pais',
		'id_departamento',
		'id_ciudad',
		'direccion',
		'id_nivel_educacion',
		'estudiando',
		'curso_estudiando',
		'trabajando',
		'lugar_trabajo',
		'salario',
		'referencia_nombre',
		'id_parentezco',
		'referencia_cedula',
		'referencia_telefono',
		'referencia_correo'
	];

	public function tipo_identificacion()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_tipo_identificacion');
	}

	public function cursos_matriculas()
	{
		return $this->hasMany(\App\Models\CursosMatricula::class, 'id_participante');
	}
}
