<?php

namespace App\Http\Controllers;

use App\Models\Centro;
use App\Models\RolesAcceso;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Models\CatalogosDetalle;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client();
        try {
            $response = $http->post(config('services.passport.login_endpoint'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => config('services.passport.client_id'),
                    'client_secret' => config('services.passport.client_secret'),
                    'username' => $request['email'],
                    'password' => $request['password']

                ]
            ]);


            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                $user = Auth::user();
              
                if ($user->fecha_verificacion_email ===null || $user->deleted_at !==null) {
                    return response()->json(['result' => false, 'message' =>
                     'El usuario se encuentra desactivado o no ha sido verificado' ]);
                }
                $token = json_decode((string) $response->getBody(), true)['access_token'];

                $accesos = RolesAcceso::
                join('roles', 'roles.id', 'roles_accesos.id_rol')
                ->join('accesos', 'roles_accesos.id_acceso', 'accesos.id')
                ->where('id_rol', $user->id_rol)
                ->select('accesos.id', 'accesos.nombre', 'accesos.descripcion', 'accesos.path', 'accesos.icon', 'accesos.orden', 'roles_accesos.ver','roles_accesos.crear','roles_accesos.editar', 'roles_accesos.eliminar')
                ->orderBy('accesos.orden', 'asc')->get();   



                $centros = UsuariosCentro::where('id_usuario', $user->id)->pluck('id_centro')->toArray();
                $departamento = CatalogosDetalle::where('id', Centro::find($centros[0])->id_departamento)->select('id', 'nombre')->first();
                $municipio = CatalogosDetalle::where('id', Centro::find($centros[0])->id_municipio)->select('id', 'nombre')->first();
                


                $log = new BitacoraHelper();
                $log->log($request, 'Inicia sesión', 'Usuarios', $request->user()->id);

                $usuario = [
                    'id' => $user->id,
                    'correo' => $user->email,
                    'rol' =>  $user->id_rol,
                    'nombre' =>  $user->nombre,
                    'departamento' => $departamento,
                    'municipio' => $municipio,
                    'is_admin' => $user->id_rol ===1 ? 1 :0
                ];
                
                return response()->json([
                    "result" => true,
                    "user" => $usuario,
                    "token" => $token,
                    "accesos" => $accesos,
                    "centros" => $centros
                ], 200);
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json(['message' =>'Petición Invalida, Por favor ingrese un usuario y contraseña'], $e->getCode());
            } elseif ($e->getCode() === 401) {
                return response()->json([ 'message' =>'Credenciales incorrectas. Intentalo nuevamente'], $e->getCode());
            }
            return response()->json([ 'message' =>  'something went wrong on the server'], $e->getCode());
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function createEmailConfirmation($user)
    {
        if ($user) {
            Mail::send('emails.confirm_email', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email)->subject('E-mail Confirmation');
            });
            return true;
        }
    }
}
