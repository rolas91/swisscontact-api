<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 07 Oct 2019 17:15:58 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Formulario
 * 
 * @property int $id
 * @property int $id_tipo
 * @property int $id_centro
 * @property int $id_curso
 * @property int $id_tema
 * @property string $nombre
 * @property string $url
 * @property \Carbon\Carbon $fecha_inicio
 * @property \Carbon\Carbon $fecha_fin
 * @property float $duracion
 * @property float $nota_maxima
 * @property int $id_usuario_creacion
 * @property int $id_usuario_modificacion
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Centro $centro
 * @property \App\Models\Curso $curso
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \App\Usuario $usuario
 * @property \Illuminate\Database\Eloquent\Collection $formularios_campos
 * @property \Illuminate\Database\Eloquent\Collection $formularios_respuestas
 * @property \Illuminate\Database\Eloquent\Collection $formularios_secciones
 *
 * @package App\Models
 */
class Formulario extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_tipo' => 'integer',
		'id_centro' => 'integer',
		'id_curso' => 'integer',
		'id_tema' => 'integer',
		'duracion' => 'float',
		'nota_maxima' => 'float',
		'id_usuario_creacion' => 'integer',
		'id_usuario_modificacion' => 'integer',
		'ordenar_aleatoriamente' => 'bool',
		'id_modo' => 'integer',
	];


	protected $fillable = [
		'id_tipo',
		'id_centro',
		'id_curso',
		'id_tema',
		'id_modo',
		'nombre',
		'url',
		'fecha_inicio',
		'fecha_fin',
		'duracion',
		'nota_maxima',
		'id_usuario_creacion',
		'id_usuario_modificacion',
		'reglas',
		'ordenar_aleatoriamente',
	];

	public function centro()
	{
		return $this->belongsTo(\App\Models\Centro::class, 'id_centro');
	}

	public function tipo()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_tipo');
	}

	public function usuario()
	{
		return $this->belongsTo(\App\Usuario::class, 'id_usuario_modificacion');
	}

	public function formularios_campos()
	{
		return $this->hasMany(\App\Models\FormulariosCampo::class, 'id_formulario');
	}

	public function formularios_respuestas()
	{
		return $this->hasMany(\App\Models\FormulariosRespuesta::class, 'id_formulario');
	}

	public function formularios_secciones()
	{
		return $this->hasMany(\App\Models\FormulariosSeccione::class, 'id_formulario');
	}
}
