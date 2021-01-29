<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'email', 'password', 'id_rol',
         'token_email_confirmation', 'fecha_verificacion_email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function role()
	{
		return $this->belongsTo(\App\Models\Role::class, 'id_rol');
	}

	public function instructores()
	{
		return $this->hasMany(\App\Models\Instructore::class, 'id_usuario');
	}

    public function centros()
	{
		return $this->belongsToMany(\App\Models\Centro::class, 'usuarios_centros', 'id_usuario', 'id_centro')
					->withPivot('id')
					->withTimestamps();
    }
    
    public function reportes()
	{
		return $this->belongsToMany(\App\Models\Reporte::class, 'reportes_usuarios', 'id_usuario', 'id_reporte')
					->withPivot('id')
					->withTimestamps();
    }

 
}
