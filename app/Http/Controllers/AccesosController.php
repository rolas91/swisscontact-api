<?php
namespace App\Http\Controllers;


use Carbon;
use App\Models\Acceso;
use App\Models\RolesAcceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class AccesosController extends Controller
{
    public function index(Request $request)
    {
        $accesos =Acceso::
        whereRaw("accesos.nombre like '%$request->filtro%'",[])
        ->selectRaw("accesos.id,accesos.nombre,accesos.descripcion,accesos.icon,accesos.path,accesos.orden,case when accesos.deleted_at is null then 'Activo' else 'Inactivo' end as estado",[])
        ->orderBy('accesos.orden','asc')
        ->get()->toArray();
        $page =$request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowPerPage >0 ? $request->rowsPerPage : 999999999999999999;
        $accesos = new Paginator($accesos, $rowsPerPage , $page  , ["path"  => $request->url(),"query" => $request->query(),]);
        return response()->json(["accesos"=> $accesos],200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
      
		//Validate inputs
		$acceso = $request["acceso"]; 
		$validator = Validator::make($acceso,
		[
			'nombre' => 'required|max:100',
			'descripcion' => 'max:250',
			'icon' => 'max:191',
			'path' => 'max:191',

		]);
		$validator->validate();
      
		try {
            DB::beginTransaction();
            $acceso = Acceso::create([
                //'id' => acceso['id']),
                'nombre' => $acceso['nombre'],
                'descripcion' => $acceso['descripcion'],
                'icon' => $acceso['icon'],
                'path' => $acceso['path'],
                'orden' => $acceso['orden']
                //'created_at' => acceso['created_at']),
                //'updated_at' => acceso['updated_at'])
            ]);

            //Insertamos los roles accesos para poder configurar los permisos
            DB::select("insert into roles_accesos  ( `id_acceso`, `id_rol`, `ver`, `crear`, `editar`, `eliminar`, `created_at`, `updated_at`)
            select * from
            (
            select  $acceso->id as id_acceso,id as id_rol,1 as ver,1 as crear,1 as editar,1 as eliminar,now() as created_at,null as updated_at  
            from roles where id =1
            union all
            select  $acceso->id as id_acceso,id as id_rol,0 as ver,0 as crear,0 as editar,0 as eliminar,now() as created_at,null as updated_at  
            from roles where id <>1
            )a
            ");

            DB::commit();

            return response()->json(
				['result' => true ],200 
			);

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

			
    }
    public function show($id)
    {
        $acceso = Acceso::findOrFail($id);
        return response()->json([ "acceso" =>  $acceso],200);
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request)
    {
        
		//Validate inputs

		$acceso = $request["acceso"]; 
		$validator = Validator::make($acceso,
		[
			'nombre' => 'required|max:100',
			'descripcion' => 'max:250',
			'icon' => 'max:191',
            'path' => 'max:191',
            'orden' => 'required|numeric'

		]);
		$validator->validate();
        $update = $request["acceso"];
        $acceso = Acceso::findOrFail($update["id"]);
        
		$acceso->id = $update['id'];
		$acceso->nombre = $update['nombre'];
		$acceso->descripcion = $update['descripcion'];
		$acceso->icon = $update['icon'];
		$acceso->path = $update['path'];
		$acceso->orden = $update['orden'];

        $acceso->save();
        return response()->json(
            ["result"=> true],201);
    }
    public function destroy($id)
    {
        $acceso = Acceso::findOrFail($id);
        $acceso->delete();
        return response()->json([],204);

    }
}