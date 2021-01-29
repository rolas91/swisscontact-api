<?php

namespace App\Http\Controllers;

use App\Exports\UsuariosExport;
use App\Usuario;
use Carbon\Carbon;
use App\Models\Centro;
use App\Models\RolesAcceso;
use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    public function getAccesos(Request $request)
    {
        $user = $request->user();
        $accesos = RolesAcceso::with(array('acceso' =>function ($q) {
            $q->select('id', 'nombre', 'path', 'icon');
        }))->where('id_rol', $user->id_rol)->orderBy('orden', 'asc')->get();

        return response()->json(['accesos' => $accesos], 200);
    }

    public function UsuariosDisponiblesinstructor(Request $request)
    {
        $filtro = $request->filtro;

        //Filtramos los usuarios que ya tienen asignado un perfil de instructor
        $usuarios =  Usuario::leftJoin('instructores', 'usuarios.id', 'instructores.id_usuario')->where([
            ['instructores.id', '=', null],
            ['fecha_verificacion_email', '<>', null],
            ['usuarios.nombre', 'like', "%$filtro%"]

        ])->select('usuarios.id', 'nombre')->get();
        return response()->json(['usuarios' => $usuarios], 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending == 'true' ? 'desc' : 'asc';


        //solamente permitimos ver usuarios con un nivel de acceso inferior al propio..es decir...centros unicamente puede editar usuarios instructores
        $roles_visibles = $user->id_rol ===1 ? "": "usuarios.id_rol > $user->id_rol AND";
        
        //validamos los usuarios con los centros que el usuario actual tienen permiso de ver
        $ids =UsuariosCentro::where('id_usuario', $user->id)->pluck('id_centro')->unique()->toArray();
        sort($ids);
        $usuarios_centros = implode(",", $ids);

        $exist_centro='';
        if ($user->id_rol !==1 && $user->id_rol !==2) {
            $exist_centro = "AND EXISTS (select 1 from usuarios_centros where usuarios_centros.id_centro in ($usuarios_centros) and usuarios_centros.id_usuario = usuarios.id)";
        }

        
        $usuarios = Usuario::
        join('roles as rol', 'usuarios.id_rol', 'rol.id')
        ->whereRaw("$roles_visibles  
        (usuarios.nombre like '%$request->filtro%' 
        OR usuarios.email like '%$request->filtro%' 
        OR rol.nombre like '%$request->filtro%'
        OR (SELECT group_concat(c.nombre SEPARATOR ', ' )  as centro
					from usuarios_centros as uc 
					inner join centros as c on uc.id_centro =c.id
					where uc.id_usuario = usuarios.id) like '%$request->filtro%'
         )  
        $exist_centro", [])
        ->selectRaw("usuarios.id,
					usuarios.nombre,
					usuarios.email,
					usuarios.fecha_verificacion_email,
					usuarios.remember_token,
					usuarios.id_rol,
                    rol.nombre as rol,
                    case when usuarios.deleted_at is null then 'Activo' else 'Inactivo' end  as estado,
                    case when usuarios.fecha_verificacion_email is null then 'No' else 'Si' end  as verificado,
                    (SELECT group_concat(c.nombre SEPARATOR ', ' )  as centro
					from usuarios_centros as uc 
					inner join centros as c on uc.id_centro =c.id
					where uc.id_usuario = usuarios.id and c.deleted_at is null) as centros_asignados
                    ", [])
        ->orderBy($sortBy, $descending)
            ->paginate($rowsPerPage, ['*'], 'Page', $page);

        return response()->json(["usuarios"=> $usuarios], 200);
    }


    public function store(Request $request)
    {
        //Validate inputs
        $usuario = $request["usuario"];
        $validator = Validator::make(
            $usuario,
            [
                'nombre' => 'required|max:191',
                'email' => 'required|max:191',
                'id_rol' => 'required|numeric',

            ]
        );
        $validator->validate();

       

        $default_password=null;
        if ($validator->fails()) {
            return response()->json(['message'=>"Todos los campos son requeridos"], 400);
        }
        try {
            $respuesta = $this->validarExisteCorreo($usuario);
            //Si el correo ya existe regresamos error
            if (!$respuesta['result']) {
                return response()->json($respuesta, 422);
            }
    

            $now = Carbon::now();
            $pass = Usuario::where([['email',$usuario['email']],['fecha_verificacion_email','=',null] ])->first();
            if (isset($pass)) {
                if ($pass->updated_at->diffInSeconds($now)<60) {
                    return response()->json([
                            'result' => false,
                            'message' => 'Debes esperar '.(60-$pass->updated_at->diffInSeconds($now)).' segundos antes de poder enviar otro email'
                        ], 500);
                }
            }


            $user = Usuario::where([['email' , $usuario['email'] ],])->first();
            $default_password = str_random(8);
            if (!isset($user)) {
                //set a random password
                DB::beginTransaction();
                $user= Usuario::create([
                        'nombre' => $usuario['nombre'],
                        'email' => $usuario['email'],
                        'password' => Hash::make($default_password),
                        'id_rol' => $usuario['id_rol']
                    ]);
            }

            //if the user exists but email not confirmed send email confirmation
            $user->password = Hash::make($default_password);
            $user->token_email_confirmation=str_random(30);
            $user->nombre = $usuario['nombre'];
            $user->id_rol = $usuario['id_rol'];
            $user->save();
          

            $_centros  = $request["centros"];
            $centros=array();
            foreach ($_centros as $centro) {
                if ($centro['checked']) {
                    array_push($centros, $centro['id']);
                }
            }
                
            $logged_user = $request->user();
            ///si es de tipo centro unicamente incluimos el centro propio
            // if (!($logged_user->id_rol ==1 OR $logged_user->id_rol ==2)) {
            //     $centros = [UsuariosCentro::where('id_usuario', $logged_user->id)->value('id_centro')];
            // }


            
            $user->centros()->attach($centros);
            $value=$this->createEmailConfirmation($user, $default_password);
            DB::commit();
            if ($value) {
                $log = new BitacoraHelper();
                $log->log($request, 'Crea Usuario', 'Usuarios', $user->id);
          
                return response()->json([
                    'result' => true,
                    'message' => "Te hemos enviado a tu correo electrónico un enlace para activar tu cuenta, por favor revisa tu correo"
                ], 200);
            } else {
                DB::rollback();
                return response()->json([
                    'result' => false,
                    'message' => "Revisa nuevamente"], 400);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

        return response()->json(['result' => true]);
    }
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json(["usuario" =>  $usuario], 200);
    }

    public function edit($id)
    {
        $usuario = Usuario::find($id);
        $_centros = $usuario->centros()->select('centros.id', 'centros.nombre')->get()->pluck('id')->toArray();
        $centros =Centro::
                selectRaw("id, nombre,false checked", [])
                ->orderBy("nombre")
                ->get();
                
        foreach ($centros as $key => $value) {
            if (in_array($value->id, $_centros)) {
                $centros[$key]->checked = true;
            } else {
                $centros[$key]->checked =false;
            }
        }
                
        return response()->json(['centros' =>$centros]);
    }

    public function update(Request $request)
    {
        //Validate inputs
        $usuario = $request["usuario"];
        $usr =  $request["usuario"];
        $validator = Validator::make(
            $usuario,
            [
                'nombre' => 'required|max:191',
                'email' => 'required|max:191',
                'id_rol' => 'required|numeric',
            ]
        );
        $validator->validate();

        //Si es activar, entonces activamos y regresamos result ok
        if ($request['activar']) {
            $usuario = Usuario::withTrashed()->find($usuario['id']);
            $usuario->restore();
            $usuario->save();
            return response()->json(
                [
                    "result" => true,
                    'message' => 'Usuario activado correctamente'
            ],
                201
            );
        }


        //Si es verificar, entonces verificamos y regresamos result ok
        if ($request['verificar']) {
            $usuario = Usuario::findOrFail($usuario['id']);
            $usuario->fecha_verificacion_email = Carbon::now();
            $usuario->token_email_confirmation = null;
            $usuario->save();
            return response()->json(
                [
                    "result" => true,
                    'message' => 'Usuario verificado correctamente'
            ],
                201
            );
        }


        try {
            DB::beginTransaction();
            $update = $request["usuario"];
            $usuario = Usuario::findOrFail($update["id"]);
    
            $respuesta = $this->validarExisteCorreo($usr);
    
            //Si el correo ya existe regresamos error
            if (!$respuesta['result']) {
                return response()->json($respuesta, 422);
            }
    
            $usuario->nombre = $update['nombre'];
            $usuario->email = $update['email'];
            $usuario->id_rol = $update['id_rol'];
            $usuario->save();
    
            $centros = array();
            $_centros  = $request["centros"];
            foreach ($_centros as $centro) {
                if ($centro['checked']) {
                    array_push($centros, $centro['id']);
                }
            }

            // ///si es de tipo centro  unicamente incluimos el centro propio
            // if (!($usuario->id_rol ==1 OR $usuario->id_rol == 2)) {
            //     $centros = array($centros[0]);
            // }


            $usuario->centros()->detach();
            $usuario->centros()->attach($centros);
            $usuario->save();

            //si el usuario no esta activo enviamos nuevamente el correo
            if ($usuario->fecha_verificacion_email ===null) {
                $default_password = str_random(8);
                $default_password=   Hash::make($default_password);
                $value=$this->createEmailConfirmation($usuario, $default_password);
            }
    
    
            $log = new BitacoraHelper();
            $log->log($request, 'Actualiza Usuario', 'Usuarios', $usuario->id);
            DB::commit();
            return response()->json(
                ["result" => true,
                 'message'=> 'usuario actualizado correctamente'
                ],
                201
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function destroy(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        $log = new BitacoraHelper();
        $log->log($request, 'Elimina Usuario', 'Usuarios', $usuario->id);
        return response()->json([], 204);
    }

    public function sendConfirmationEmail($id)
    {
        $usuario = Usuario::findOrFail($id);
        $now = Carbon::now();
        $pass = Usuario::where([['email',$usuario->email],['fecha_verificacion_email','=',null] ])->first();
        if (isset($pass)) {
            if ($pass->updated_at->diffInSeconds($now)<60) {
                return response()->json([
                            'message' => 'Debes esperar '.(60-$pass->updated_at->diffInSeconds($now)).' segundos antes de poder enviar otro email'
                        ], 500);
            }
        }

        $value=$this->createEmailConfirmation($usuario, $usuario->password);

        return response()->json([
                'message' => 'Te hemos enviado otro email, por favor revisa tu correo'
            ], 200);
    }

    public function createEmailConfirmation($usuario, $default_password =null)
    {
        if ($usuario) {
            Mail::send('emails.confirmation_email', ['usuario' => $usuario, 'default_password' =>$default_password, 'cambiar_contrasenia' => false], function ($message) use ($usuario) {
                $message->from('no-reply@competenciasparaganar.com')->to($usuario->email)->subject('E-mail Confirmation');
            });
            return true;
        }
    }

    public function ValidarExisteCorreo($usuario)
    {
        $existe_correo = false;
        $correo = "";
        //Validamos que el correo no exista para otro usuario
        //validamos si es de tipo array o tipo objecto para hacer la consulta
        if (gettype($usuario) === "array") {
            $existe_correo =  Usuario::withTrashed()->where([
                ['id','<>', $usuario['id']],
                ['email', '=',$usuario['email']]
            ])->first() !==null ;
            $correo = $usuario['email'];
        } else {
            $existe_correo =  Usuario::withTrashed()->where([
                ['id','<>', $usuario->id],
                ['email', '=',$usuario->email]
            ])->first() !==null ;
            $correo = $usuario->email;
        }
        //Si el correo ya existe mandamos error
        if ($existe_correo) {
            return
                [
                    'result' => false,
                    'message' => "El correo '$correo' ya se encuentra registrado para otro usuario"
                ];
        } else {
            return
            [
                'result' => true,
                'message' => "correo no esta registrado"
            ];
        }
    }

    public function VerificarCorreo(Request $request, $return_array =false)
    {

        //TODO remove this code
        return
        [
            'result' => true,
            'message' => 'Token valido'
        ];
    
        $token = $request->token;
        $correo = $request->Correo;
        $result ="";
        if (!$token || !$correo) {
            $result =[
                'result' => false,
                'message' => 'El token o correo no es inválido'
            ];

            if ($return_array) {
                return $result;
            } else {
                return response()->json($result, 500);
            }
        }

        //obtenemos el primer usuario con el correo que coincida
        $usuario = Usuario::withTrashed()->where('email', '=', $correo)->firstOrFail();
        if ($usuario ===null) {
            $result =[
                'result' => false,
                'message' => 'no se encontró usuario registrado con el correo '.$correo
            ];

            if ($return_array) {
                return $result;
            } else {
                return response()->json($result, 404);
            }
        }

        //validamos el token
        $token_valido =  $usuario->token_email_confirmation === $correo;
        if (!$token_valido) {
            $result =  [
                'result' => false,
                'message' => 'El token o correo no es inválido'
            ];

            if ($return_array) {
                return $result;
            } else {
                return response()->json($result, 500);
            }
        }

        //Si llegamos a este punto el token es valido
        $result =  [
            'result' => true,
            'message' => 'Token valido'
        ];

        if ($return_array) {
            return $result;
        } else {
            return response()->json($result, 200);
        }
    }

    public function ResetearContrasenia(Request $request)
    {
        $correo = $request['correo'];

        //Primero validamos que el correo exista
        $usuario = Usuario::where('email', '=', $request['email'])->firstOrFail();
        if ($usuario) {
            
            //Si existe proseguimos  a enviar un correo electronico
            $now = Carbon::now();
            $pass = Usuario::where([['email',$usuario->email],['fecha_verificacion_email','=',null] ])->first();
            if (isset($pass)) {
                if ($pass->updated_at->diffInSeconds($now)<60) {
                    return response()->json([
                            'message' => 'Debes esperar '.(60-$pass->updated_at->diffInSeconds($now)).' segundos antes de poder enviar otro email'
                        ], 500);
                }
            }
            $cambiar_contrasenia=true;

            $usuario->updated_at = Carbon::now();
            $usuario->token_email_confirmation=str_random(30);
            $usuario->save();

            Mail::send('emails.confirmation_email', ['usuario' => $usuario, 'default_password' =>'password', 'cambiar_contrasenia' => $cambiar_contrasenia], function ($message) use ($usuario) {
                $message->from('no-reply@competenciasparaganar.com')->to($usuario->email)->subject('E-mail Confirmation');
            });

           

            return response()->json(['result'=> true,'message' =>'Hemos enviado un correo con un enlace que te permitirá reseterar tu contraseña']);
        }
    }

    public function CambiarContrasenia(Request $request)
    {
        $password = $request['password'];
        $password_confirmation = $request['passwordConfirmation'];

        $response =  $this->VerificarCorreo($request, true);

        if ($response['result'] ===true) {
            if ($password !==$password_confirmation) {
                return response()->json(
                    [
                        'result' => false,
                        'message' => 'las contraseñas no coinciden'
                    ],
                    500
                );
            }

            $usuario = Usuario::withTrashed()->where('email', '=', $request['email'])->firstOrFail();
            $usuario->password = Hash::make($request['password']);
            $usuario->token_email_confirmation = null;
            $usuario->fecha_verificacion_email =Carbon::now();
            $usuario->save();

            // $log = new BitacoraHelper();
            // $log->log($request,'Cambia Contraseña usuario','Usuarios',$usuario->id);

            return response()->json(
                [
                    'result' => true,
                    'message' => 'contraseña cambiada correctamente'
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'El token o correo no es inválido'
        ],
                500
            );
        }
    }

    public function DescargarExcel(Request $request)
    {
        $user = $request->user();
        $filtro = $request->filtro;
        $export = new UsuariosExport($user, $filtro);

        $log = new BitacoraHelper();
        $log->log($request, 'Exporta usuarios a excel', 'Usuario', null);
        return $export->download('usuarios.xlsx');
    }
}
