<?php

namespace App\Http\Controllers;

use Exception;
use App\Usuario;
use App\Models\Curso;
use App\Models\Centro;
use App\Models\Instructore;
use App\Models\RolesAcceso;
use Illuminate\Http\Request;
use App\Exports\CentrosExport;
use App\Models\UsuariosCentro;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CentrosController extends Controller
{
    public function index(Request $request)
    {

        
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';

        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        //si es admin agregamos todos los centros
        if($usuario->id_rol ==1 or $usuario->id_rol ==2){
            $id_centros = implode(",", Centro::withTrashed()->get()->pluck('id')->unique()->toArray());
        }


        $centros = Centro::withTrashed()
            ->join('catalogos_detalles as departamento', 'centros.id_departamento', 'departamento.id')
            ->join('catalogos_detalles as municipio', 'centros.id_municipio', 'municipio.id')
            ->join('catalogos_detalles as pais', 'centros.id_pais', 'pais.id')
            ->join('catalogos_detalles as tipo', 'centros.id_tipo', 'tipo.id')


            ->whereRaw("centros.nombre like '%$request->filtro%' and centros.id in ($id_centros)", [])
            ->selectRaw("centros.id,
					centros.nombre,
					centros.id_tipo,
					centros.id_pais,
					centros.id_departamento,
					centros.id_municipio,
					centros.lema,
					centros.logo,
					centros.banner,
					centros.foto_representante,
					centros.descripcion,
					centros.quienes_somos,
					centros.mision,
					centros.vision,
					centros.valores,
					centros.direccion,
					centros.latitud,
					centros.longitud,
					centros.contacto_nombre,
					centros.contacto_telefono,
					centros.contacto_correo,
					centros.telefono,
					centros.correo,
					centros.web_url,
					centros.facebook,
					centros.instagram,
					centros.twitter,
					centros.youtube,
					centros.computadoras,
					centros.tablets,
                    centros.celulares,
                    centros.deleted_at,
					departamento.nombre as departamento,
                    municipio.nombre as municipio,
                    case when centros.deleted_at is null then 'Activo' else 'Inactivo' end as estado, 
					pais.nombre as pais,
                    tipo.nombre as tipo", [])
                    ->orderBy($sortBy, $descending)
                     ->paginate($rowsPerPage, ['*'], 'Page', $page);

        $centros_id = $centros->map(function ($centros) {
            return collect($centros)->only(['id'])->all();
        })->pluck('id');
       

        //Obtenemos todos los cursos relacionados para cada centros
        $cursos = Centro::withTrashed()->
        join('catalogo_cursos', 'catalogo_cursos.id_centro', 'centros.id')
        ->join('cursos', 'catalogo_cursos.id', 'cursos.id_curso')
        ->join('catalogos_detalles as tipo', 'tipo.id', 'catalogo_cursos.id_tipo')
        ->whereIn('catalogo_cursos.id_centro', $centros_id)
        ->select('cursos.id', 'catalogo_cursos.nombre', 'cursos.codigo', 'tipo.nombre as tipo', 'centros.id as id_centro')->get();
        return response()->json(["centros" => $centros , 'cursos' => $cursos], 200);
    }
    public function create()
    {
        //
    }


    public function reactivarCentro($id)
    {
        $centro = Centro::withTrashed()->findOrFail($id);
        $centro->restore();
        return response()->json(['result'=> true, 'centro eliminado correctamente']);
    }

    public function store(Request $request)
    {
        $PermissionRole =  RolesAcceso::where([
                ['id_acceso',3],
                ['id_rol',$request->user()->id_rol],
                ['crear',1]
        ])->get();

        if (count($PermissionRole) ==0) {
            throw new Exception("No tiene permisos suficientes para crear un centro");
        }


        //Validate inputs

        $centro = $request["centro"];
        $validator = Validator::make(
            $centro,
            [
                'nombre' => 'required|max:191',
                'id_pais' => 'required|numeric',
                'id_departamento' => 'required|numeric',
                'id_municipio' => 'required|numeric',
                'id_tipo' => 'required|numeric',
                'lema' => 'max:191',
                'logo' => 'max:500',
                'banner' => 'max:500',
                'descripcion' => 'max:2000',
                'quienes_somos' => 'max:2000',
                'mision' => 'max:2000',
                'vision' => 'max:2000',
                'valores' => 'max:2000',
                'direccion' => 'required|max:2000',
                'latitud' => 'max:191',
                'longitud' => 'max:191',
                'contacto_nombre' => 'required|max:191',
                'contacto_telefono' => 'max:191',
                'contacto_correo' => 'max:191',
                'telefono' => 'max:191',
                'correo' => 'max:191',
                'web_url' => 'max:191',
                'facebook' => 'max:191',
                'instagram' => 'max:191',
                'twitter' => 'max:191',
                'youtube' => 'max:191',
                'computadoras' => 'required|numeric',
                'tablets' => 'required|numeric',
                'celulares' => 'required|numeric',
                'foto_representante' => 'max:500',

            ]
        );
        $validator->validate();

        $centro = Centro::create([
            //'id' => centro['id']),
            'nombre' => $centro['nombre'],
            'id_tipo' => $centro['id_tipo'],
            'id_pais' => $centro['id_pais'],
            'id_departamento' => $centro['id_departamento'],
            'id_municipio' => $centro['id_municipio'],
            'lema' => $centro['lema'],
            'logo' => $centro['logo'],
            'banner' => $centro['banner'],
            'descripcion' => $centro['descripcion'],
            'quienes_somos' => $centro['quienes_somos'],
            'mision' => $centro['mision'],
            'vision' => $centro['vision'],
            'valores' => $centro['valores'],
            'direccion' => $centro['direccion'],
            'latitud' => $centro['latitud'],
            'longitud' => $centro['longitud'],
            'contacto_nombre' => $centro['contacto_nombre'],
            'contacto_telefono' => $centro['contacto_telefono'],
            'contacto_correo' => $centro['contacto_correo'],
            'telefono' => $centro['telefono'],
            'correo' => $centro['correo'],
            'web_url' => $centro['web_url'],
            'facebook' => $centro['facebook'],
            'instagram' => $centro['instagram'],
            'twitter' => $centro['twitter'],
            'youtube' => $centro['youtube'],
            'computadoras' => $centro['computadoras'],
            'tablets' => $centro['tablets'],
            'celulares' => $centro['celulares'],
            'foto_representante' => $centro['foto_representante']
        
        ]);


        //obtenemos todos los admins y super admins aunq estén deshabilitados para asiganarles el nuevo centro (esto es porque si lo habilitan luego no les aparecería el centro)
        $admin_users = Usuario::withTrashed()->whereIn('id_rol',[1,2])->get();
        foreach($admin_users as $key => $usr){
            UsuariosCentro::create([
                'id_usuario' => $usr->id,
                'id_centro' => $centro->id
            ]);
        }
       

        $log = new BitacoraHelper();
        $log->log($request, 'Crea Centro', 'Centro', $centro->id);

        return response()->json(['result' => true,'centro' => $centro], 200);
    }
    public function show($id)
    {
        $centro = Centro::findOrFail($id);
        return response()->json(["centro" =>  $centro], 200);
    }
    public function edit($id)
    {
        $centro = Centro::findOrFail($id);
        $centro->descripcion = $centro->descripcion !==null?  $centro->descripcion : '';
        return response()->json(["centro" =>  $centro], 200);
    }
    public function update(Request $request)
    {

        //Validate inputs
        $centro =  $request["centro"];
        $validator = Validator::make(
            $centro,
            [
                'nombre' => 'required|max:191',
                'id_pais' => 'required|numeric',
                'id_departamento' => 'required|numeric',
                'id_municipio' => 'required|numeric',
                'id_tipo'=> 'required|numeric',
                'lema' => 'max:191',
                'logo' => 'max:500',
                'banner' => 'max:500',
                'descripcion' => 'max:2000',
                'quienes_somos' => 'max:2000',
                'mision' => 'max:2000',
                'vision' => 'max:2000',
                'valores' => 'max:2000',
                'direccion' => 'required|max:2000',
                'latitud' => 'max:191',
                'longitud' => 'max:191',
                'contacto_nombre' => 'required|max:191',
                'contacto_telefono' => 'max:191',
                'contacto_correo' => 'max:191',
                'telefono' => 'max:191',
                'correo' => 'max:191',
                'web_url' => 'max:191',
                'facebook' => 'max:191',
                'instagram' => 'max:191',
                'twitter' => 'max:191',
                'youtube' => 'max:191',
                'computadoras' => 'required|numeric',
                'tablets' => 'required|numeric',
                'celulares' => 'required|numeric',
                'foto_representante' => 'max:500'

            ]
        );
        $validator->validate();
        $update = $request["centro"];
        $centro = Centro::findOrFail($update["id"]);

        $centro->id = $update['id'];
        $centro->nombre = $update['nombre'];
        $centro->id_pais = $update['id_pais'];
        $centro->id_departamento = $update['id_departamento'];
        $centro->id_municipio = $update['id_municipio'];
        $centro->id_tipo = $update['id_tipo'];
        $centro->lema = $update['lema'];

        $centro->descripcion = $update['descripcion'];
        $centro->quienes_somos = $update['quienes_somos'];
        $centro->mision = $update['mision'];
        $centro->vision = $update['vision'];
        $centro->valores = $update['valores'];
        $centro->direccion = $update['direccion'];
        $centro->latitud = $update['latitud'];
        $centro->longitud = $update['longitud'];
        $centro->contacto_nombre = $update['contacto_nombre'];
        $centro->contacto_telefono = $update['contacto_telefono'];
        $centro->contacto_correo = $update['contacto_correo'];
        $centro->telefono = $update['telefono'];
        $centro->correo = $update['correo'];
        $centro->web_url = $update['web_url'];
        $centro->facebook = $update['facebook'];
        $centro->instagram = $update['instagram'];
        $centro->twitter = $update['twitter'];
        $centro->youtube = $update['youtube'];
        $centro->computadoras = $update['computadoras'];
        $centro->tablets = $update['tablets'];
        $centro->celulares = $update['celulares'];
        $centro->id_tipo = $update['id_tipo'];
        $centro->save();

        $log = new BitacoraHelper();
        $log->log($request, 'Actualiza Centro', 'Centro', $centro->id);


        return response()->json(["result" => true , 'centro' => $centro], 201);
    }
    public function destroy(Request $request, $id)
    {
        $centro = Centro::findOrFail($id);
        $centro->delete();

        $log = new BitacoraHelper();
        $log->log($request, 'Elimina Centro', 'Centro', $centro->id);
        return response()->json([], 204);
    }

    public function uploadimage(Request $request)
    {
        $_centro = json_decode($request->centro);
        $centro = Centro::findOrFail($_centro->id);
        $url_logo = "";
        $url_banner = "";
        $url_foto_representante= "";
        $delete_logo = $_centro->logo ===null;
        $delete_banner = $_centro->banner ===null;
        $delete_foto_representante = $_centro->foto_representante ===null;
        if ($delete_logo) {
            $image_path = public_path('img/logos/') . $centro->logo;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $centro->logo = null;
            $centro->save();
        }

        if ($delete_banner) {
            $image_path = public_path('img/banners/') . $centro->banner;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $centro->banner = null;
            $centro->save();
        }

        if ($delete_foto_representante) {
            $image_path = public_path('img/fotos_representantes/') . $centro->foto_representante;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $centro->foto_representante = null;
            $centro->save();
        }

        if ($request->hasFile('imagen')) {
            $file      = $request->file('imagen');
            $logo   = date('YmdHis') . '-' . str_pad($centro->id, 6, "0", STR_PAD_LEFT);
            $file->move(public_path('img/logos/'), $logo);
            $image_path = public_path('img/logos/') . $centro->logo;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $centro->logo = $logo;
            $centro->save();
            $url_logo =  env('APP_URL') . '/img/logos/' . $logo;
        }


        if ($request->hasFile('banner')) {
            $file      = $request->file('banner');
            $banner   = date('YmdHis') . '-' . str_pad($centro->id, 6, "0", STR_PAD_LEFT);
            ;
            $file->move(public_path('img/banners/'), $banner);
            
            $image_path = public_path('img/banners/') . $centro->banner;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $centro->banner = $banner;
            $centro->save();
            $url_banner = env('APP_URL') . '/img/banners/' . $banner;
        }

        if ($request->hasFile('foto_representante')) {
            $file      = $request->file('foto_representante');
            $foto_representante   = date('YmdHis') . '-' . str_pad($centro->id, 6, "0", STR_PAD_LEFT);
            $file->move(public_path('img/fotos_representantes/'), $foto_representante);
            $image_path = public_path('img/fotos_representantes/') . $centro->foto_representante;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
            $centro->foto_representante = $foto_representante;
            $centro->save();
            $url_foto_representante =  env('APP_URL') . '/img/fotos_representantes/' . $foto_representante;
        }

        return response()->json(
            [
                "message" => "Image Uploaded Succesfully",
                'urlLogo' => $url_logo,
                'urlBanner' => $url_banner,
                'urlFotoRepresentante' =>$url_foto_representante
            ]
        );
    }
    public function CargarInstructores(Request $request)
    {
        $id_centro = $request['id_centro'];
        $id_curso = $request['id_curso'];
        if (!$id_centro && $id_curso) {
            $id_centro = Curso::with('catalogo_curso')->find($id_curso)->catalogo_curso->id_centro;
        }
        //esto es en caso de ser un usuario admin o super admin y está creando el curso
        if (!$id_centro) {
            return response()->json(['result' => false, 'message' => 'Debe seleccionar un centro primero']);
        }
        $usuarios_centros = DB::table('usuarios_centros')
        ->join('usuarios', 'usuarios_centros.id_usuario', 'usuarios.id')
        ->where('usuarios_centros.id_centro', $id_centro)->select("usuarios.id")->get()->pluck('id')->toArray();
  
        $_instructores = Instructore::whereIn('id_usuario', $usuarios_centros)->get()->pluck('id')->toArray();

        $query=null;
        try {
            $query =Curso::find($id_curso)->instructores();
        } catch (\Throwable $th) {
            //throw $th;
        }
        $selecccionados =null;
        if ($query && $query->count()>0) {
            $selecccionados = $query->select("instructores.id")->get()->pluck('id')->toArray();
        }
        
        $instructores = DB::table('instructores')->selectRaw("id, concat(nombres,' ',apellidos) as nombre,false checked", [])
        ->whereIn('id', $_instructores)
        ->orderBy("nombres")
            ->get();
        foreach ($instructores as $key => $value) {
            if (isset($selecccionados)) {
                if ($selecccionados && in_array($value->id, $selecccionados)) {
                    $instructores[$key]->checked = true;
                }
            } else {
                $instructores[$key]->checked = false;
            }
        }

        $logged_user = $request->user();
        $usuario_centro = UsuariosCentro::where('id_usuario', $logged_user->id)->value('id_centro');
        $id_centro = $logged_user->id_rol ===1 || $logged_user->id_rol ===2 ?  $id_centro : $usuario_centro;

        $centro = Centro::findOrFail($id_centro);
        return response()->json([ 'id_departamento'=> $centro->id_departamento, 'id_municipio' => $centro->id_municipio, 'instructores' => $instructores], 200);
    }

    public function DescargarExcel(Request $request)
    {
        $export = new CentrosExport();
        $log = new BitacoraHelper();
        $log->log($request, 'Exporta centros a excel', 'Centro', null);
        return $export->download('centros.xlsx');
    }
}
