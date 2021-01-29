<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 11 Sep 2019 22:05:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CursosInstructore
 * 
 * @property int $id
 * @property int $id_curso
 * @property int $id_instructor
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Curso $curso
 * @property \App\Models\Instructore $instructore
 *
 * @package App\Models
 */
class CursosInstructore extends Eloquent
{
	protected $casts = [
		'id_curso' => 'integer',
		'id_instructor' => 'integer'
	];

	protected $fillable = [
		'id_curso',
		'id_instructor'
	];

	public function curso()
	{
		return $this->belongsTo(\App\Models\Curso::class, 'id_curso');
	}

	public function instructore()
	{
		return $this->belongsTo(\App\Models\Instructore::class, 'id_instructor');
	}
}
