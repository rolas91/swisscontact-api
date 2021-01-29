<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 04 Dec 2019 09:35:29 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CorreosEnviado
 * 
 * @property int $id
 * @property int $id_curso
 * @property int $id_centro
 * @property int $id_participante
 * @property string $correo
 * @property string $formulario
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Centro $centro
 * @property \App\Models\Curso $curso
 * @property \App\Models\Participante $participante
 *
 * @package App\Models
 */
class CorreosEnviado extends Eloquent
{
	protected $casts = [
		'id_curso' => 'integer',
		'id_centro' => 'integer',
		'id_participante' => 'integer'
	];

	protected $fillable = [
		'id_curso',
		'id_centro',
		'id_participante',
		'correo',
		'formulario'
	];

	public function centro()
	{
		return $this->belongsTo(\App\Models\Centro::class, 'id_centro');
	}

	public function curso()
	{
		return $this->belongsTo(\App\Models\Curso::class, 'id_curso');
	}

	public function participante()
	{
		return $this->belongsTo(\App\Models\Participante::class, 'id_participante');
	}
}
