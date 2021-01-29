<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 07 Oct 2019 17:15:58 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FormulariosSeccione
 * 
 * @property int $id
 * @property int $id_formulario
 * @property string $titulo
 * @property string $descripcion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Formulario $formulario
 * @property \Illuminate\Database\Eloquent\Collection $formularios_campos
 *
 * @package App\Models
 */
class FormulariosSeccione extends Eloquent
{
	protected $casts = [
		'id_formulario' => 'integer'
	];

	protected $fillable = [
		'id_formulario',
		'titulo',
		'descripcion'
	];

	public function formulario()
	{
		return $this->belongsTo(\App\Models\Formulario::class, 'id_formulario');
	}

	public function formularios_campos()
	{
		return $this->hasMany(\App\Models\FormulariosCampo::class, 'id_seccion');
	}
}
