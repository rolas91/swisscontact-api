<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 25 Jul 2019 21:37:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CatalogosDetalle
 * 
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $id_catalogo
 * @property string $descripcion
 * @property string $valor
 * @property bool $activo
 * @property int $id_padre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class CatalogosDetalle extends Eloquent
{
	protected $casts = [
		'id_catalogo' => 'integer',
		'activo' => 'bool',
		'id_padre' => 'integer'
	];

	protected $fillable = [
		'codigo',
		'nombre',
		'id_catalogo',
		'descripcion',
		'valor',
		'activo',
		'id_padre'
	];
}
