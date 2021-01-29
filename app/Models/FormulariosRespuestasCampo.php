<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 26 Sep 2019 05:33:08 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FormulariosRespuestasCampo
 * 
 * @property int $id
 * @property int $id_formulario_campo
 * @property string $valor
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\FormulariosCampo $formularios_campo
 *
 * @package App\Models
 */
class FormulariosRespuestasCampo extends Eloquent
{
	protected $casts = [
		'id_formulario_respuesta' => 'integer',
		'id_formulario_campo' => 'integer'
	];

	protected $fillable = [
		'id_formulario_respuesta',
		'id_formulario_campo',
		'valor',
		'nota',
		'evaluada'
	];

	public function formularios_campo()
	{
		return $this->belongsTo(\App\Models\FormulariosCampo::class, 'id_formulario_campo');
	}

	public function formularios_respuesta()
	{
		return $this->belongsTo(\App\Models\FormulariosRespuesta::class, 'id_formulario_respuesta');
	}
}
