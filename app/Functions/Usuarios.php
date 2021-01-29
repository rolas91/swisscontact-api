<?php

namespace  App\Functions;

use Exception;
use App\Usuario;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



class Usuarios
{
    public static function CrearUsuarioinstructor($request, $usuario)
    {

            
            $user = Usuario::withTrashed()->where([['email', $usuario->email],])->first();
            $default_password = str_random(8);
            if (!isset($user)) {
                //set a random password
              
                $usuario = Usuario::create([
                    'nombre' => $usuario->nombre,
                    'email' => $usuario->email,
                    'password' => Hash::make($default_password),
                    'id_rol' => $usuario->id_rol
                ]);
            }else{
                throw new Exception("El correo '$user->email' ya se encuentra registrado");
                //$usuario  =$user;
            }

            //if the user exists but email not confirmed send email confirmation
           
            $usuario->id = $usuario->id;
            $usuario->token_email_confirmation = str_random(30);
            $usuario->nombre = $usuario->nombre;
            $usuario->id_rol = $usuario->id_rol;
            $usuario->save();


            $logged_user = $request->user();
            $_centros  = $request["centros"];
            $centros=array();
	            foreach ($_centros as $centro) {
					if($centro['checked']){
						array_push($centros, $centro['id']);
					}
				}
            ///si es de tipo centro unicamente incluimos el centro propio
            if( !($logged_user->id_rol == 1 || $logged_user->id_rol == 2)){
                $centros = [UsuariosCentro::where('id_usuario',$logged_user->id)->value('id_centro')];
            }
        
            $usuario->centros()->attach($centros);
            $value = self::createEmailConfirmation($usuario, $default_password);
      
        return $usuario;
      
    }

    public static function createEmailConfirmation($usuario, $default_password = null)
    {
        if ($usuario) {
            Mail::send('emails.confirmation_email', ['usuario' => $usuario, 'default_password' => $default_password, 'cambiar_contrasenia'=>false], function ($message) use ($usuario) {
                $message->to($usuario->email)->subject('Confirmación de cuenta Competencias Para Ganar');
            });
            return true;
        }
    }
}
