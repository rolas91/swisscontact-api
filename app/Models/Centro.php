<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 11 Sep 2019 22:05:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Centro
 * 
 * @property int $id
 * @property string $nombre
 * @property int $id_pais
 * @property int $id_departamento
 * @property int $id_municipio
 * @property string $lema
 * @property string $logo
 * @property string $banner
 * @property string $descripcion
 * @property string $quienes_somos
 * @property string $mision
 * @property string $vision
 * @property string $valores
 * @property string $direccion
 * @property string $latitud
 * @property string $longitud
 * @property string $contacto_nombre
 * @property string $contacto_telefono
 * @property string $contacto_correo
 * @property string $telefono
 * @property string $correo
 * @property string $web_url
 * @property string $facebook
 * @property string $instagram
 * @property string $twitter
 * @property string $youtube
 * @property int $computadoras
 * @property int $tablets
 * @property int $celulares
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\CatalogosDetalle $catalogos_detalle
 * @property \Illuminate\Database\Eloquent\Collection $instructores
 * @property \Illuminate\Database\Eloquent\Collection $cursos
 *
 * @package App\Models
 */
class Centro extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'id_pais' => 'integer',
		'id_tipo' => 'integer',
		'id_departamento' => 'integer',
		'id_municipio' => 'integer',
		'computadoras' => 'integer',
		'tablets' => 'integer',
		'celulares' => 'integer',
		'velocidad_internet'
	];

	protected $fillable = [
		'nombre',
		'id_tipo',
		'id_pais',
		'id_departamento',
		'id_municipio',
		'lema',
		'logo',
		'banner',
		'descripcion',
		'quienes_somos',
		'mision',
		'vision',
		'valores',
		'direccion',
		'latitud',
		'longitud',
		'contacto_nombre',
		'contacto_telefono',
		'contacto_correo',
		'telefono',
		'correo',
		'web_url',
		'facebook',
		'instagram',
		'twitter',
		'youtube',
		'computadoras',
		'tablets',
		'celulares',
		'velocidad_internet',
		'foto_representante'
	];

	public function pais()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_pais');
	}

	public function departamentos()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_departamento');
	}

	public function municipio()
	{
		return $this->belongsTo(\App\Models\CatalogosDetalle::class, 'id_municipio');
	}

	public function cursos()
	{
		return $this->hasMany(\App\Models\CatalogoCurso::class, 'id_centro');
	}

	public function usuarios()
	{
		return $this->belongsToMany(\App\Usuario::class, 'usuarios_centros', 'id_centro', 'id_usuario')
			->withPivot('id')
			->withTimestamps();
	}
}
