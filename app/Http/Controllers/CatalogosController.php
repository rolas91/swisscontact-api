<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Acceso;
use App\Models\Centro;
use App\Models\instructore;
use App\Models\RolesAcceso;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\CatalogoCurso;
use App\Models\UsuariosCentro;
use App\Models\CursosMatricula;
use App\Models\CatalogosDetalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CatalogosController extends Controller
{
    public function Roles(Request $request)
    {
        $data = [];

        $user = $request->user();

        if ($request->has('q')) {
            $search = $request->q;
            if ($user->id_rol ==1) {
                $data   = Role::
                select("id", "nombre")
                ->where([
                    ['nombre', 'like', "$search%"],
                    ['id','<>',5]
                ])
               ->get();
            } else {
                $data   = Role::
                select("id", "nombre")
                ->where([
                    ['nombre', 'like', "$search%"],
                    ['id','>',$user->id_rol],
                     ['id','<>',5]
                    ])
                ->get();
            }
        } else {

            //si es super usuario removemos esta validacion
            if ($user->id_rol ==1) {
                $data = Role::
                    select("id", "nombre")
                    ->get();
            } else {
                $data = Role::
                where([['id','>',$user->id_rol]])
                    ->select("id", "nombre")
                    ->get();
            }
        }

        return response()->json(['roles' => $data], 200);
    }

    public function nivelesAcademicos(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['nombre', 'like', "$search%"], ['id_catalogo', '=', 32], ['activo', 1]])
                ->take(100)->get();
        } else {
            $data = CatalogosDetalle::
                select("id", "nombre")
                ->where([['id_catalogo', '=', 32], ['activo', 1]])
                ->take(100)->get();
        }

        return response()->json(['nivel_academicos' => $data], 200);
    }

    public function paises(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['nombre', 'like', "$search%"], ['id_catalogo', '=', 29], ['activo', 1]])
                ->get();
        } else {
            $data = CatalogosDetalle::
                select("id", "nombre")
                ->where([['id_catalogo', '=', 29], ['activo', 1]])
                ->get();
        }

        return response()->json(['paises' => $data], 200);
    }

    public function departamentos(Request $request)
    {
        $data = [];
        if ($request->has('q')) {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre", "id_padre")
                ->where([['nombre', 'LIKE', "%$search%"], ['id_catalogo', '=', 30], ['activo', 1]])
                ->whereIn('id_padre', explode(',', $request->id_padre))
                ->orderBy('nombre')->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre", "id_padre")
                ->where([['id_catalogo', '=', 30],  ['activo', 1]])
                ->whereIn('id_padre', explode(',', $request->id_padre))
                ->orderBy('nombre')->get();
        }

        return response()->json(['departamentos' => $data], 200);
    }

    public function municipios(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['nombre', 'LIKE', "%$search%"], ['activo', 1], ['id_catalogo', 31], ['id_padre', $request->id_padre]])
                ->orderBy('nombre')->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['activo', 1], ['id_catalogo', 31], ['id_padre', $request->id_padre]])
                ->orderBy('nombre')->get();
        }
        return response()->json(['municipios' => $data], 200);
    }

    public function TipoIdentificaciones(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ['id_catalogo', '=', 33], ["activo", 1]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 33]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["tipo_identificaciones" => $data], 200);
    }

    public function Sectores(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1],  ['id_catalogo', '=', 34]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 34]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["sectores" => $data], 200);
    }

    public function Categorias(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1],  ['id_catalogo', '=', 34]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 34]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["categorias" => $data], 200);
    }
    public function Subcategorias(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1],  ['id_catalogo', '=', 35], ['id_padre', $request->id_padre]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 35], ['id_padre', $request->id_padre]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["subcategorias" => $data], 200);
    }

    public function UnidadDuraciones(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', '=', 36]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 36]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["unidad_duraciones" => $data], 200);
    }
    public function NivelDificultades(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', '=', 37]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1],  ['id_catalogo', '=', 37]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["nivel_dificultades" => $data], 200);
    }
    public function EstadosCurso(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', '=', 38]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 38]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["estados_curso" => $data], 200);
    }
    public function TipoCursos(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', '=', 39]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', '=', 39]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["tipo_cursos" => $data], 200);
    }

    public function instructores(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = instructore::selectRaw("id,concat(nombres,' ',apellidos) as nombre", [])
                ->where([["nombre", "LIKE", "%$search%"]])
                ->orderBy("nombres")
                ->orderBy("apellidos")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = instructore::selectRaw("id,concat(nombres,' ',apellidos) as nombre", [])
                ->orderBy("nombres")
                ->orderBy("apellidos")
                ->take(100)
                ->get();
        }
        return response()->json(["instructores" => $data], 200);
    }

    public function Centros(Request $request)
    {
        $data = [];

        if ($request->has("filtro")) {
            $search = $request->filtro;
            $data   = Centro::selectRaw("id, nombre,false checked", [])
                ->where([["nombre", "LIKE", "%$search%"]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = Centro::selectRaw("id, nombre,false checked", [])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["centros" => $data], 200);
    }

    public function CentrosCursos(Request $request)
    {
        $data = [];
        $search = $request->filter;
        $data   = Centro::
        join('catalogo_cursos', 'catalogo_cursos.id_centro', 'centros.id')
        ->join('cursos', 'catalogo_cursos.id', 'cursos.id_curso')
       // ->join('cursos_matriculas as cm', 'cm.id_curso', 'cursos.id')
       ->whereRaw('centros.deleted_at is null', [])
        ->selectRaw("centros.id, centros.nombre,false checked", [])
        ->distinct()
        ->orderBy("nombre")->get();

        return response()->json(["centros" => $data], 200);
    }

    public function Cursos(Request $request)
    {
        $data = [];

        //Filtramos los cursos x centros
        if ($request->has('id_centro')) {
            $search = $request->filter;
            $data   = CatalogoCurso::
            join('cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->selectRaw("cursos.id,concat(cursos.codigo, ' - ', catalogo_cursos.nombre)  as nombre", [])
            ->where([['id_centro',$request['id_centro']]])
              ->where([["nombre", "LIKE", "%$search%"]])
            ->orderBy("cursos.created_at", 'desc')
            ->get();

            return response()->json(["cursos" => $data], 200);
        }

        $user = $request->user();
        $centros =[];
        if ($user) {
            $centros = UsuariosCentro::where('id_usuario', $user->id)->pluck('id_centro')->unique()->toArray();
        } else {
            $centros = UsuariosCentro::all()->pluck('id_centro')->unique()->toArray();
        }
     
        //Filtramos los centros por nombre
        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogoCurso::
                   join('cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->selectRaw("cursos.id,concat(cursos.codigo, ' - ', catalogo_cursos.nombre)  as nombre", [])
                ->where([["nombre", "LIKE", "%$search%"]])
                ->whereIn('catalogo_cursos.id_centro', $centros)
              ->orderBy("cursos.created_at", 'desc')
                ->get();
        }

        



        //Si ninguna de las condiciones anteriores se cumple simplemente obtenemos todos los cursos
        if (!$request->has('id_centro') && !$request->has('filter')) {
            $data   = CatalogoCurso::
             join('cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->selectRaw("cursos.id,concat(cursos.codigo, ' - ', catalogo_cursos.nombre)  as nombre", [])
            ->whereIn('catalogo_cursos.id_centro', $centros)
            ->orderBy("cursos.created_at", 'desc')
            ->get();
        }

        return response()->json(["cursos" => $data], 200);
    }


    public function UsuarioCursos(Request $request)
    {
        $data = [];

        //Filtramos los cursos x centros
        if ($request->has('id_centro')) {
            $search = $request->filter;
            $data   = CatalogoCurso::
            join('cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->selectRaw("cursos.id,concat(cursos.codigo, ' - ', catalogo_cursos.nombre)  as nombre", [])
            ->where([['id_centro',$request['id_centro']]])
              ->where([["nombre", "LIKE", "%$search%"]])
            ->orderBy("cursos.created_at", 'desc')
            ->get();

            return response()->json(["cursos" => $data], 200);
        }

        $user = $request->user();
        $centros =[];
        if ($user) {
            $centros = UsuariosCentro::where('id_usuario', $user->id)->pluck('id_centro')->unique()->toArray();
        } else {
            $centros = UsuariosCentro::all()->pluck('id_centro')->unique()->toArray();
        }
     
        //Filtramos los centros por nombre
        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogoCurso::
                join('cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->selectRaw("cursos.id,concat(cursos.codigo, ' - ', catalogo_cursos.nombre)  as nombre, catalogo_cursos.id_centro", [])
                ->where([["nombre", "LIKE", "%$search%"]])
                ->whereIn('catalogo_cursos.id_centro', $centros)
              ->orderBy("cursos.created_at", 'desc')
                ->get();
        }


        //Si ninguna de las condiciones anteriores se cumple simplemente obtenemos todos los cursos
        if (!$request->has('id_centro') && !$request->has('filter')) {
            $data   = DB::table('catalogo_cursos')
            ->join('cursos', 'cursos.id_curso', 'catalogo_cursos.id')
            ->selectRaw("concat(cursos.codigo, ' - ', catalogo_cursos.nombre) as id,concat(cursos.codigo, ' - ', catalogo_cursos.nombre)  as nombre", [])
            ->whereIn('catalogo_cursos.id_centro', $centros)
            ->orderBy("cursos.created_at", 'desc')
            ->get();
        }

        return response()->json(["cursos" => $data], 200);
    }





    public function EstadoCiviles(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', 40]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', 40]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["estado_civiles" => $data], 200);
    }


    public function Modalidades(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', 41]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', 41]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["modalidades" => $data], 200);
    }

    public function Modos(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', 42]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', 42]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["modos" => $data], 200);
    }

    public function Participantes(Request $request)
    {

        //Para el envio de correos en los formularios
        if ($request->has('id_curso')) {
            $participantes = CursosMatricula::
            where('id_curso', $request['id_curso'])
            ->selectRaw("id, concat(nombres_participante,' ', apellidos_participante) as nombre, correo,
            ifNull((select 1 from correos_enviados where correos_enviados.correo = cursos_matriculas.correo  limit 1),0) as correo_enviado", [])
            ->get();

            foreach ($participantes as $key => $participante) {
                $participantes[$key]->correo_enviado = $participante->correo_enviado ===1;
            }
            return response()->json(["participantes" => $participantes], 200);
        }
        $search = $request->has('q') ?  $request->q : '';
        $data   = Participante::selectRaw("id, concat(nombres,' ', apellidos,' - ',documento_identidad) as nombre", [])
            ->whereRaw("concat(participantes.nombres, ' ', participantes.apellidos) like '%$search%' OR participantes.documento_identidad  like '%$search%' ", [])
            ->orderBy("nombres")
            ->orderBy("apellidos")
            ->take(100)
            ->get();
        return response()->json(["participantes" => $data], 200);
    }


    public function Parentescos(Request $request)
    {
        $data = [];

        if ($request->loadAll) {
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['id_catalogo', 43]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
            return response()->json(["parentescos" => $data], 200);
        }



        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', 43]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', 43]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["parentescos" => $data], 200);
    }


    public function Accesos(Request $request)
    {
        $data = [];
        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = Acceso::
                selectRaw("id as id_acceso, nombre,false as ver, false as crear,false as editar, false as eliminar", [])
                ->where([["nombre", "LIKE", "%$search%"]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = Acceso::
                selectRaw("id as id_acceso, nombre,false as ver, false as crear,false as editar, false as eliminar", [])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        //Esta hechizada es porque el false llega como 0 al cliente
        foreach ($data as $key => $value) {
            $data[$key]->ver = false;
            $data[$key]->crear = false;
            $data[$key]->editar = false;
            $data[$key]->eliminar = false;
        }
        return response()->json(["accesos" => $data], 200);
    }

    public function AccesosEdit(Request $request)
    {
        $data = [];
        $id_rol = $request->id_rol;
        $data   = RolesAcceso::
        join('accesos', 'roles_accesos.id_acceso', 'accesos.id')
        ->select('id_acceso', 'crear', 'editar', 'eliminar', 'ver', 'id_rol', 'accesos.nombre as acceso')
        ->where('id_rol', $id_rol)->get();
        return response()->json(["accesos" => $data, 'id_rol' => (int) $id_rol], 200);
    }

    public function tipoFormulario(Request $request)
    {
        $data = [];

        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', 44]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', 44]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["tipos_formularios" => $data], 200);
    }



    public function TipoCampoFormulario(Request $request)
    {
        $data = [];
        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalog', 45]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalog', 45]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["tipos_campos_formularios" => $data], 200);
    }


    public function TemasFormularios(Request $request)
    {
        $data = [];
        if ($request->has("filter")) {
            $search = $request->filter;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["nombre", "LIKE", "%$search%"], ["activo", 1], ['id_catalogo', 46]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        } else {
            $search = $request->q;
            $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([["activo", 1], ['id_catalogo', 46]])
                ->orderBy("nombre")
                ->take(100)
                ->get();
        }
        return response()->json(["temas" => $data], 200);
    }

    public function CatalogoCursos(Request $request)
    {
        $data = [];
        $user = $request->user();
        $centros = UsuariosCentro::where('id_usuario', $user->id)->pluck('id_centro')->unique()->toArray();
        //validamos si tiene asignado mas de un centro procedemos a buscar el parametro id_centro
        $id_centro=0;
        if (count($centros)) {
            $id_centro = $request->has('id_centro')?  $request['id_centro']: $centros[0];
        }
        $search = $request->filter;
        $data   = CatalogoCurso::
                select("id", "nombre")
                ->whereIn('id_centro', [$id_centro])
                ->orderBy("id", 'desc')
                ->get();
        return response()->json(["catalogo_cursos" => $data], 200);
    }


    public function TodosMunicipios(Request $request)
    {
        $search = $request->filtro;
        $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['nombre', 'LIKE', "%$search%"], ['activo', 1], ['id_catalogo', 31]])
                ->orderBy('nombre')->get();
        return response()->json(['municipios' => $data], 200);
    }


    public function tipoCentros(Request $request)
    {
        $search = $request->filtro;
        $data   = CatalogosDetalle::
                select("id", "nombre")
                ->where([['nombre', 'LIKE', "%$search%"], ['activo', 1], ['id_catalogo', 47]])
                ->orderBy('nombre')->get();
        return response()->json(['tipoCentros' => $data], 200);
    }
}
