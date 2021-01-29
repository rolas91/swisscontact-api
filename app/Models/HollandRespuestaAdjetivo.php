<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 04 Dec 2019 13:56:59 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class HollandRespuestaAdjetivo
 *
 * @property int $id
 * @property int $respuesta_id
 * @property int $adjetivo_id
 *
 * @property \App\Models\HollandRespuestum $holland_respuestum
 *
 * @package App\Models
 */
class HollandRespuestaAdjetivo extends Eloquent
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'respuesta_id' => 'integer',
        'adjetivo_id' => 'integer'
    ];

    protected $fillable = [
        'respuesta_id',
        'adjetivo_id'
    ];

    public function holland_respuestum()
    {
        return $this->belongsTo(\App\Models\HollandRespuestum::class, 'respuesta_id');
    }

    public function holland_adjetivo()
    {
        return $this->belongsTo(\App\Models\HollandAdjetivo::class, 'adjetivo_id');
    }
}
