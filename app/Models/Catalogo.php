<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jul 2019 21:37:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Catalogo
 * 
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property bool $activo
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Catalogo extends Eloquent
{
	protected $casts = [
		'activo' => 'bool'
	];

	protected $fillable = [
		'codigo',
		'nombre',
		'activo'
	];
}
