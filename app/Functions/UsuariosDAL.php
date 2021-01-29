<?php

namespace  App\Functions;

use Illuminate\Http\Request;
use App\Models\UsuariosCentro;
use App\Usuario;
use Illuminate\Support\Facades\DB;

class UsuariosDAL
{
    public $usuario;
    public $filtro;
    public function __construct($usuario, $filtro)
    {
        $this->usuario = $usuario;
        $this->filtro = $filtro;
    }

    public function getAllUsuarios()
    {
        $user = $this->usuario;
        $filtro = $this->filtro;

        //solamente permitimos ver usuarios con un nivel de acceso inferior al propio..es decir...centros unicamente puede editar usuarios instructores
        $roles_visibles = $user->id_rol ===1 ? "": "usuarios.id_rol > $user->id_rol AND";
        
        //validamos los usuarios con los centros que el usuario actual tienen permiso de ver
        $ids =UsuariosCentro::where('id_usuario', $user->id)->pluck('id_centro')->unique()->toArray();
        sort($ids);
        $usuarios_centros = implode(",", $ids);


        $exist_centro = "AND EXISTS (select 1 from usuarios_centros where usuarios_centros.id_centro in ($usuarios_centros) and usuarios_centros.id_usuario = usuarios.id)";

        
        $usuarios = Usuario::
        join('roles as rol', 'usuarios.id_rol', 'rol.id')
        ->whereRaw("$roles_visibles  
        (usuarios.nombre like '%$filtro%' 
        OR usuarios.email like '%$filtro%' 
        OR rol.nombre like '%$filtro%'
        OR (SELECT group_concat(c.nombre SEPARATOR ', ' )  as centro
					from usuarios_centros as uc 
					inner join centros as c on uc.id_centro =c.id
					where uc.id_usuario = usuarios.id) like '%$filtro%'
         )  
        $exist_centro", [])
        ->selectRaw("usuarios.id,
					usuarios.nombre,
					usuarios.email,
                    rol.nombre as rol,
                    case when usuarios.deleted_at is null then 'Activo' else 'Inactivo' end  as estado,
                    case when usuarios.fecha_verificacion_email is null then 'No' else 'Si' end  as verificado,
                    (SELECT group_concat(c.nombre SEPARATOR ', ' )  as centro
					from usuarios_centros as uc 
					inner join centros as c on uc.id_centro =c.id
					where uc.id_usuario = usuarios.id) as centros_asignados
                    ", [])
                    ->get();

        return $usuarios;
    }
}
