<?php

namespace App\Functions;

use App\Models\CatalogoCurso;
use App\Usuario;
use App\Models\CorreosEnviado;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Emails
{
    public static function EnviarEmailEnlaceFormulario($usuarios, $formulario)
    {
        foreach ($usuarios as $key => $usuario) {
            Mail::send(
                'emails.enlace_formulario',
                [
                'usuario' => $usuario,
                'formulario' =>  $formulario
            ],
                function ($message) use ($usuario) {
                    $message->from('no-reply@competenciasparaganar.com')->to($usuario->correo)->subject('Enlace para responder formulario');
                }
            );

            $correo = CorreosEnviado::create([
                'id_curso' => $usuario->id_curso,
                'id_centro' => $usuario->id_centro,
                'id_participante' => $usuario->id_participante,
                'correo' => $usuario->correo,
                'formulario' => $usuario->correo
                ]);
        }
    }


    public static function EnviarCorreo($id_usuario_conectado, $administradores, $asunto, $plantilla, $data)
    {
        $usuario_conectado = Usuario::findOrFail($id_usuario_conectado);
        foreach ($administradores as $key => $admin) {
            $correo = $admin->email;
            Mail::send($plantilla, ['data' => $data, 'admin' => $admin, 'usuario_conectado' => $usuario_conectado], function ($message) use ($correo,$asunto) {
                $message->from('no-reply@competenciasparaganar.com')->to($correo)->subject($asunto);
            });
        }
        return true;
    }

    public static function EnviarCorreoPrueba($correo)
    {
        Mail::send("emails.email_prueba", [], function ($message) use ($correo) {
            $message->from('no-reply@competenciasparaganar.com')->to($correo)->subject("Mensaje de prueba");
        });
    }


    public static function EnviarCorreoCursoAprobado($curso, $usuario_aprueba, $usuario_destino)
    {
        $correo = $usuario_destino->email;
        $nombre_curso = CatalogoCurso::find($curso->id_curso)->nombre;
        
        Mail::send("emails.curso_aprobado", ['admin' =>$usuario_aprueba,'curso' =>$curso, 'usuario' =>$usuario_destino, 'nombre_curso' =>$nombre_curso], function ($message) use ($correo,$nombre_curso) {
            $message->from('no-reply@competenciasparaganar.com')->to($correo)->subject("El curso $nombre_curso ha sido aprobado");
        });
    }
}
