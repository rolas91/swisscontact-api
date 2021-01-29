<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 21 Oct 2019 15:35:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Reporte
 * 
 * @property int $id
 * @property int $id_datasource
 * @property string $nombre
 * @property string $configuracion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $usuarios
 *
 * @package App\Models
 */
class Reporte extends Eloquent
{
	protected $casts = [
		'id_datasource' => 'integer',
		'id_usuario' => 'integer'
	];

	protected $fillable = [
		'id_datasource',
		'nombre',
		'configuracion',
		'id_usuario'
	];

	public function usuarios()
	{
		return $this->belongsToMany(\App\Usuario::class, 'reportes_usuarios', 'id_reporte', 'id_usuario')
			->withPivot('id')
			->withTimestamps();
	}
}
