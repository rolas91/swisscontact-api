<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 22 Sep 2019 22:08:03 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Acceso
 * 
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $icon
 * @property string $path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $roles
 *
 * @package App\Models
 */
class Acceso extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $fillable = [
		'nombre',
		'descripcion',
		'icon',
		'path',
		'orden'
	];

	public function roles()
	{
		return $this->belongsToMany(\App\Models\Role::class, 'roles_accesos', 'id_acceso', 'id_rol')
					->withPivot('id', 'ver', 'crear', 'editar', 'eliminar')
					->withTimestamps();
	}
}
