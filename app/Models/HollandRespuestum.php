<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 27 Jan 2020 11:41:04 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class HollandRespuestum
 * 
 * @property int $id
 * @property \Carbon\Carbon $creado
 * @property \Carbon\Carbon $actualizado
 * @property int $tiempo
 * @property int $participante_id
 * @property string $parte_b
 * @property string $parte_c
 * @property string $parte_d_1
 * @property string $parte_d_2
 * @property string $parte_d_3
 * @property string $parte_d_4
 * @property string $parte_d_5
 * @property int $test_id
 * @property string $cuadro_resumen
 * @property \Carbon\Carbon $hora_finalizado
 * @property int $id_holland_test
 * 
 * @property \App\Models\HollandTest $holland_test
 * @property \Illuminate\Database\Eloquent\Collection $holland_respuesta_adjetivos
 *
 * @package App\Models
 */
class HollandRespuestum extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'tiempo' => 'integer',
		'participante_id' => 'integer',
		'test_id' => 'integer',
		'id_holland_test' => 'integer'
	];

	protected $dates = [
		'creado',
		'actualizado',
		'hora_finalizado'
	];

	protected $fillable = [
		'creado',
		'actualizado',
		'tiempo',
		'participante_id',
		'parte_b',
		'parte_c',
		'parte_d_1',
		'parte_d_2',
		'parte_d_3',
		'parte_d_4',
		'parte_d_5',
		'test_id',
		'cuadro_resumen',
		'hora_finalizado',
		'id_holland_test'
	];

	public function holland_test()
	{
		return $this->belongsTo(\App\Models\HollandTest::class, 'id_holland_test');
	}

	public function holland_respuesta_adjetivos()
	{
		return $this->hasMany(\App\Models\HollandRespuestaAdjetivo::class, 'respuesta_id');
	}
}
