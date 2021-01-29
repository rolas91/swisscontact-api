<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 22 Sep 2019 22:19:21 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $accesos
 * @property \Illuminate\Database\Eloquent\Collection $usuarios
 *
 * @package App\Models
 */
class Role extends Eloquent
{

	protected $casts = [
		'nivel' => 'integer',
	];

	protected $fillable = [
		'nombre',
		'nivel'
	];

	public function accesos()
	{
		return $this->belongsToMany(\App\Models\Acceso::class, 'roles_accesos', 'id_rol', 'id_acceso')
			->withPivot('id', 'ver', 'crear', 'editar', 'eliminar')
			->withTimestamps();
	}

	public function usuarios()
	{
		return $this->hasMany(\App\Usuario::class, 'id_rol');
	}
}
