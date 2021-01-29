<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 21 Oct 2019 15:35:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Bitacora
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $model
 * @property int $id_model
 * @property string $ip_address
 * @property string $user_agent
 * @property string $url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Bitacora extends Eloquent
{
    protected $table = 'bitacora';

    protected $casts = [
        'user_id' => 'integer',
        'id_model' => 'integer'
    ];

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'id_model',
        'ip_address',
        'user_agent',
        'url'
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Usuario::class, 'user_id');
    }
}
