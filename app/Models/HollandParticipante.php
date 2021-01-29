<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Jan 2020 15:31:22 -0600.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class HollandParticipante
 *
 * @property int $id
 * @property \Carbon\Carbon $creado
 * @property \Carbon\Carbon $actualizado
 * @property int $test_id
 * @property int $id_participante
 * @property string $correo
 * @property string $token
 * @property \Carbon\Carbon $invitacion_enviada
 * @property string $personalidad
 * @property string $telefono
 * @property string $cedula
 * @property string $nombres
 * @property string $apellidos
 *
 * @package App\Models
 */
class HollandParticipante extends Eloquent
{
    protected $table = 'holland_participante';
    public $timestamps = false;

    protected $casts = [
        'test_id' => 'integer',
        'id_participante' => 'integer'
    ];

    // protected $dates = [
    // 	'creado',
    // 	'actualizado',
    // 	'invitacion_enviada'
    // ];

    protected $hidden = [
        'token'
    ];

    protected $fillable = [
        'creado',
        'actualizado',
        'test_id',
        'id_participante',
        'correo',
        'token',
        'invitacion_enviada',
        'personalidad',
        'telefono',
        'cedula',
        'nombres',
        'apellidos'
    ];
}
