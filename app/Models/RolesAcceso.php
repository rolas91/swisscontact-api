<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 22 Sep 2019 22:08:03 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class RolesAcceso
 * 
 * @property int $id
 * @property int $id_acceso
 * @property int $id_rol
 * @property bool $ver
 * @property bool $crear
 * @property bool $editar
 * @property bool $eliminar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Acceso $acceso
 * @property \App\Models\Role $role
 *
 * @package App\Models
 */
class RolesAcceso extends Eloquent
{
	protected $casts = [
		'id_acceso' => 'integer',
		'id_rol' => 'integer',
		'ver' => 'bool',
		'crear' => 'bool',
		'editar' => 'bool',
		'eliminar' => 'bool'
	];

	protected $fillable = [
		'id_acceso',
		'id_rol',
		'ver',
		'crear',
		'editar',
		'eliminar'
	];

	public function acceso()
	{
		return $this->belongsTo(\App\Models\Acceso::class, 'id_acceso');
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class, 'id_rol');
	}
}
