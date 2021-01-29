<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 26 Sep 2019 05:33:07 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FormulariosRespuesta
 *
 * @property int $id
 * @property int $id_formulario
 * @property int $id_participante
 * @property int $id_evaluador
 * @property \Carbon\Carbon $fecha_inicio
 * @property \Carbon\Carbon $fecha_fin
 * @property float $nota
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Usuario $usuario
 * @property \App\Models\Formulario $formulario
 * @property \App\Models\Participante $participante
 *
 * @package App\Models
 */
class FormulariosRespuesta extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $casts = [
        'id_formulario' => 'integer',
        'id_participante' => 'integer',
        'id_evaluador' => 'integer',
        'id_centro' =>  'integer',
        'id_curso' => 'integer',
        'nota' => 'float'
    ];


    protected $fillable = [
        'id_formulario',
        'id_participante',
        'id_evaluador',
        'fecha_inicio',
        'fecha_fin',
        'duracion',
        'nota',
        'id_centro',
        'id_curso',
        'nombre_participante',
        'correo_participante',
        'slug'
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Usuario::class, 'id_evaluador');
    }

    public function formulario()
    {
        return $this->belongsTo(\App\Models\Formulario::class, 'id_formulario');
    }

    public function participante()
    {
        return $this->belongsTo(\App\Models\Participante::class, 'id_participante');
    }

    public function curso()
    {
        return $this->belongsTo(\App\Models\Curso::class, 'id_curso');
    }

    public function formularios_respuestas_campos()
    {
        return $this->hasMany(\App\Models\FormulariosRespuestasCampo::class, 'id_formulario_respuesta');
    }
}
