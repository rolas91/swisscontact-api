<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 21 Oct 2019 15:35:14 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ReportesUsuario
 * 
 * @property int $id
 * @property int $id_reporte
 * @property int $id_usuario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Reporte $reporte
 * @property \App\Usuario $usuario
 *
 * @package App\Models
 */
class ReportesUsuario extends Eloquent
{
	protected $casts = [
		'id_reporte' => 'integer',
		'id_usuario' => 'integer'
	];

	protected $fillable = [
		'id_reporte',
		'id_usuario'
	];

	public function reporte()
	{
		return $this->belongsTo(\App\Models\Reporte::class, 'id_reporte');
	}

	public function usuario()
	{
		return $this->belongsTo(\App\Usuario::class, 'id_usuario');
	}
}
