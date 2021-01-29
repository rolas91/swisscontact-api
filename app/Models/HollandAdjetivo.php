<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 04 Dec 2019 13:56:15 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class HollandAdjetivo
 * 
 * @property int $id
 * @property string $texto
 * @property string $dimension
 * @property \Carbon\Carbon $creado
 * @property \Carbon\Carbon $actualizado
 *
 * @package App\Models
 */
class HollandAdjetivo extends Eloquent
{
	protected $table = 'holland_adjetivo';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'integer'
	];

	protected $dates = [
		'creado',
		'actualizado'
	];

	protected $fillable = [
		'texto',
		'dimension',
		'creado',
		'actualizado'
	];
}
