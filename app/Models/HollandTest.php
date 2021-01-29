<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Jan 2020 09:32:24 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class HollandTest
 *
 * @property int $id
 * @property string $nombre
 * @property \Carbon\Carbon $fecha_inicio
 * @property \Carbon\Carbon $fecha_fin
 * @property int $usuario_creacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $token
 *
 * @property \App\Usuario $usuario
 * @property \Illuminate\Database\Eloquent\Collection $holland_respuesta
 *
 * @package App\Models
 */
class HollandTest extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $casts = [
        'usuario_creacion' => 'integer'
    ];

    // protected $dates = [
    //     'fecha_inicio',
    //     'fecha_fin'
    // ];

    protected $hidden = [
        'token'
    ];

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'usuario_creacion',
        'token'
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Usuario::class, 'usuario_creacion');
    }

    public function holland_respuesta()
    {
        return $this->hasMany(\App\Models\HollandRespuestum::class, 'id_holland_test');
    }
}
