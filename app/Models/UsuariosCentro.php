<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 23 Sep 2019 06:58:12 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UsuariosCentro
 * 
 * @property int $id
 * @property int $id_usuario
 * @property int $id_centro
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Centro $centro
 * @property \App\Usuario $usuario
 *
 * @package App\Models
 */
class UsuariosCentro extends Eloquent
{
	protected $casts = [
		'id_usuario' => 'integer',
		'id_centro' => 'integer'
	];

	protected $fillable = [
		'id_usuario',
		'id_centro'
	];

	public function centro()
	{
		return $this->belongsTo(\App\Models\Centro::class, 'id_centro');
	}

	public function usuario()
	{
		return $this->belongsTo(\App\Usuario::class, 'id_usuario');
	}
}
