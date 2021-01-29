<?php

namespace App\Http\Controllers;


use Carbon;
use App\Models\RolesAcceso;
use Illuminate\Http\Request;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class RolesAccesosController extends Controller
{
	public function index(Request $request)
	{

		$roles_accesos = null;

		//Si es super admin
		if($request->user()->id_rol ===1){

			$roles_accesos = RolesAcceso::
			join('accesos as acceso', 'roles_accesos.id_acceso', 'acceso.id')
			->join('roles as rol', 'roles_accesos.id_rol', 'rol.id')
			->distinct()->selectRaw("rol.id as id_rol, rol.nombre as rol", [])
			->get()->toArray();
		}
		else{

			$roles_accesos = RolesAcceso::
			join('accesos as acceso', 'roles_accesos.id_acceso', 'acceso.id')
			->join('roles as rol', 'roles_accesos.id_rol', 'rol.id')
			->distinct()->selectRaw("rol.id as id_rol, rol.nombre as rol", [])
			->where([['rol.id','<>',1],
			['rol.id', '>', $request->user()->id_rol]
			])
			->get()->toArray();
		}


		$page = $request->page == 0 ? 1 : $request->page;
		$rowsPerPage = $request->rowPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
		$roles_accesos = new Paginator($roles_accesos, $rowsPerPage, $page, ["path"  => $request->url(), "query" => $request->query(),]);
		return response()->json(["roles_accesos" => $roles_accesos], 200);
	}
	public function create()
	{
		//
	}
	public function store(Request $request)
	{
		//Validate inputs
		$roles_accesos = $request["roles_accesos"];

		$validator = Validator::make(
			$request->all(),
			[
				'roles_accesos' => 'required'
			]
		);
		$validator->validate();

		try {
			DB::beginTransaction();
			if(RolesAcceso::where('id_rol', $roles_accesos[0]['id_rol'])->count()>0){
				RolesAcceso::where('id_rol', $roles_accesos[0]['id_rol'])->delete();
			}
			RolesAcceso::insert($roles_accesos);


			$log = new BitacoraHelper();
			$log->log($request,'Crea RolAccesos','Rol',$roles_accesos[0]['id_rol']);
			DB::commit(); 
			return response()->json(['result' => true], 200);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
		//validamos que no se pueda guardar el rol mas de una vez
		
	}
	public function show($id)
	{
		$roles_acceso = RolesAcceso::findOrFail($id);
		return response()->json(["roles_acceso" =>  $roles_acceso], 200);
	}
	public function edit($id)
	{
		//
	}
	public function update(Request $request)
	{
		$roles_accesos = $request["roles_accesos"];

		$validator = Validator::make(
			$request->all(),
			[
				'roles_accesos' => 'required'
			]
		);
		$validator->validate();
		try {
			DB::beginTransaction();
			RolesAcceso::where('id_rol', $roles_accesos[0]['id_rol'])->delete();
			foreach ($roles_accesos as $key => $rol_acceso) {
				unset($roles_accesos[$key]['acceso']);
			}
			RolesAcceso::insert($roles_accesos);

			$log = new BitacoraHelper();
			$log->log($request,'Actualizar RolAccesos','Rol',$roles_accesos[0]['id_rol']);
			DB::commit();
			return response()->json(['result' => true], 200);
		} catch (\Throwable $th) {
			DB::rollback();
			throw $th;
		}
	}
	public function destroy(Request $request,$id)
	{
		$roles_acceso = RolesAcceso::findOrFail($id);
		$roles_acceso->delete();

		$log = new BitacoraHelper();
			$log->log($request,'Eliminar RolAccesos','Rol',$roles_acceso['id_rol']);
		return response()->json([], 204);
	}
}
