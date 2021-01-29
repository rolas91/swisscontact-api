<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 11 Sep 2019 22:05:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CursosMatricula
 * 
 * @property int $id
 * @property int $id_curso
 * @property int $id_participante
 * @property string $nombres_participante
 * @property string $apellidos_participante
 * @property string $telefono
 * @property int $id_tipo_identificacion
 * @property string $documento_identidad
 * @property int $edad
 * @property int $id_estado_civil
 * @property string $sexo
 * @property int $id_pais
 * @property int $id_departamento
 * @property int $id_municipio
 * @property string $direccion
 * @property int $id_nivel_academico
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
 * @property int $calificacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Curso $curso
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \App\Models\Participante $participante
 *
 * @package App\Models
 */
class CursosMatricula extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_curso' => 'integer',
		'id_participante' => 'integer',
		'id_tipo_identificacion' => 'integer',
		'edad' => 'integer',
		'id_estado_civil' => 'integer',
		'id_pais' => 'integer',
		'id_departamento' => 'integer',
		'id_municipio' => 'integer',
		'id_nivel_academico' => 'integer',
		'estudiando' => 'bool',
		'trabajando' => 'bool',
		'salario' => 'float',
		'id_parentezco' => 'integer',
		'calificacion' => 'integer',
		'egresado' => 'bool'
	];

	protected $fillable = [
		'id_curso',
		'id_participante',
		'nombres_participante',
		'apellidos_participante',
		'telefono',
		'correo',
		'id_tipo_identificacion',
		'documento_identidad',
		'edad',
		'id_estado_civil',
		'sexo',
		'id_pais',
		'id_departamento',
		'id_municipio',
		'direccion',
		'id_nivel_academico',
		'estudiando',
		'curso_estudiando',
		'trabajando',
		'lugar_trabajo',
		'salario',
		'referencia_nombre',
		'id_parentezco',
		'referencia_cedula',
		'referencia_telefono',
		'referencia_correo',
		'calificacion',
		'fecha_nacimiento',
		'id_test_holland',
		'egresado',
		'comentarios'
	];

	public function curso()
	{
		return $this->belongsTo(\App\Models\Curso::class, 'id_curso');
	}

	public function catalogos_detalle()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_tipo_identificacion');
	}

	public function participante()
	{
		return $this->belongsTo(\App\Models\Participante::class, 'id_participante');
	}

	public function test_holland()
	{
		return $this->belongsTo(\App\Model\HollandTest::class, 'id_test_holland');
	}
}
