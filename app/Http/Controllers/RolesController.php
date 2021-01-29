<?php

namespace App\Http\Controllers;


use Carbon;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::whereRaw("roles.nombre like '%$request->filtro%'", [])
            ->selectRaw("roles.id,roles.nombre,roles.created_at,roles.updated_at", [])
            ->get()->toArray();
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
        $roles = new Paginator($roles, $rowsPerPage, $page, ["path"  => $request->url(), "query" => $request->query(),]);
        return response()->json(["roles" => $roles], 200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {

        //Validate inputs

        $role = $request["role"];
        $validator = Validator::make(
            $role,
            [
                'nombre' => 'required|max:191',

            ]
        );

        DB::beginTransaction();
        try {

            $validator->validate();
            $nivel = (int) Role::max('nivel') + 1;

            $rol = Role::create([
                'nombre' => $role['nombre'],
                'nivel' => $nivel
            ]);

            DB::select(DB::raw('insert into roles_accesos ( id_acceso, id_rol,ver,crear,editar,eliminar, created_at,updated_at) select distinct id_acceso,7,0,0,0,0,now(),null FROM competencias_para_ganar.roles_accesos;'), []);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
        }





        $log = new BitacoraHelper();
        $log->log($request, 'Crea Rol', 'Rol', $rol->id);

        return response()->json(
            ['result' => true],
            200
        );
    }
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json(["role" =>  $role], 200);
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request)
    {

        //Validate inputs

        $role = $request["role"];
        $validator = Validator::make(
            $role,
            [
                'nombre' => 'required|max:191',

            ]
        );
        $validator->validate();
        $update = $request["role"];
        $role = Role::findOrFail($update["id"]);

        $role->id = $update['id'];
        $role->nombre = $update['nombre'];

        $role->save();

        $log = new BitacoraHelper();
        $log->log($request, 'Actualiza Rol', 'Rol', $role->id);
        return response()->json(
            ["result" => true],
            201
        );
    }
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $role = Role::findOrFail($id);
            $role->accesos()->detach();
            $role->delete();

            $log = new BitacoraHelper();
            $log->log($request, 'Elimina Rol', 'Rol', $role->id);
            DB::commit();
            return response()->json([], 204);
        }catch(\Exception $ex){
            DB::rollback();
        }
       
    }
}
