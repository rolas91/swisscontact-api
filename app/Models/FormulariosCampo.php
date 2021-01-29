<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 07 Oct 2019 17:15:58 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FormulariosCampo
 * 
 * @property int $id
 * @property int $id_formulario
 * @property int $id_seccion
 * @property int $id_tipo
 * @property string $texto
 * @property bool $requerido
 * @property string $tipo_input
 * @property string $opciones
 * @property float $nota
 * @property bool $editando
 * @property string $temp
 * 
 * @property \App\Models\Formulario $formulario
 * @property \App\Models\FormulariosSeccione $formularios_seccione
 * @property \Illuminate\Database\Eloquent\Collection $formularios_respuestas_campos
 *
 * @package App\Models
 */
class FormulariosCampo extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'id_formulario' => 'integer',
		'id_seccion' => 'integer',
		'id_tipo' => 'integer',
		'requerido' => 'bool',
		'nota' => 'float',
		'editando' => 'bool'
	];

	protected $fillable = [
		'id_formulario',
		'id_seccion',
		'id_tipo',
		'texto',
		'requerido',
		'tipo_input',
		'opciones',
		'nota',
		'editando',
		'temp',
		'respuesta',
		'imagen',
		'subtitulo',
		'minimo',
		'maximo',
		'respuesta_correcta',
		'arregloTable'
	];

	public function formulario()
	{
		return $this->belongsTo(\App\Models\Formulario::class, 'id_formulario');
	}

	public function formularios_seccione()
	{
		return $this->belongsTo(\App\Models\FormulariosSeccione::class, 'id_seccion');
	}

	public function formularios_respuestas_campos()
	{
		return $this->hasMany(\App\Models\FormulariosRespuestasCampo::class, 'id_formulario_campo');
	}
}
