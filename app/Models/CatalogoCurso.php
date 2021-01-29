<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 23 Nov 2019 12:23:01 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CatalogoCurso
 * 
 * @property int $id
 * @property int $id_tipo
 * @property int $id_centro
 * @property int $id_sector
 * @property int $id_unidad_duracion
 * @property string $nombre
 * @property string $descripcion
 * @property string $competencias_adquiridas
 * @property int $duracion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Centro $centro
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \Illuminate\Database\Eloquent\Collection $cursos
 *
 * @package App\Models
 */
class CatalogoCurso extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_tipo' => 'integer',
		'id_centro' => 'integer',
		'id_sector' => 'integer',
		'id_unidad_duracion' => 'integer',
		'duracion' => 'integer'
	];

	protected $fillable = [
		'id_tipo',
		'id_centro',
		'id_sector',
		'id_unidad_duracion',
		'nombre',
		'descripcion',
		'competencias_adquiridas',
		'duracion'
	];

	public function centro()
	{
		return $this->belongsTo(\App\Models\Centro::class, 'id_centro');
	}

	public function tipo()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_tipo');
	}

	public function catalogos_detalle()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_unidad_duracion');
	}

	public function cursos()
	{
		return $this->hasMany(\App\Models\Curso::class, 'id_curso');
	}
}
