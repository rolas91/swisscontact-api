<?php
namespace App\Http\Controllers;

use DB;
use Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\CursosInstructore;

class CursosInstructoresController extends Controller
{
    public function index(Request $request)
    {
        $cursos_instructores =CursosInstructore::
        join('cursos as curso', 'cursos_instructores.id_curso','curso.id')
		->join('instructores as instructor', 'cursos_instructores.id_instructor','instructor.id')
		
        
        ->whereRaw("cursos_instructores.nombre like '%$request->filtro%'",[])
        ->selectRaw("cursos_instructores.id,
					cursos_instructores.id_curso,
					cursos_instructores.id_instructor,
					curso.nombre as curso,
					instructor.nombre as instructor",[])
        ->get()->toArray();
        $page =$request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowPerPage >0 ? $request->rowsPerPage : 999999999999999999;
        $cursos_instructores = new Paginator($cursos_instructores, $rowsPerPage , $page  , ["path"  => $request->url(),"query" => $request->query(),]);
        return response()->json(["cursos_instructores"=> $cursos_instructores],200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
      
		//Validate inputs

		$cursos_instructore = $request["cursos_instructore"]; 
		$validator = Validator::make($cursos_instructore,
		[
			'id_curso' => 'required|numeric',
			'id_instructor' => 'required|numeric',

		]);
		$validator->validate();
      
		CursosInstructore::create([
			//'id' => cursos_instructore['id']),
			'id_curso' => $cursos_instructore['id_curso'],
			'id_instructor' => $cursos_instructore['id_instructor'],
			//'created_at' => cursos_instructore['created_at']),
			//'updated_at' => cursos_instructore['updated_at'])
		]);

			return response()->json(
				['result' => true ],200 
			);
    }
    public function show($id)
    {
        $cursos_instructore = CursosInstructore::findOrFail($id);
        return response()->json([ "cursos_instructore" =>  $cursos_instructore],200);
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request)
    {
        
		//Validate inputs

		$cursos_instructore = $request["cursos_instructore"]; 
		$validator = Validator::make($cursos_instructore,
		[
			'id_curso' => 'required|numeric',
			'id_instructor' => 'required|numeric',

		]);
		$validator->validate();
        $update = $request["cursos_instructore"];
        $cursos_instructore = CursosInstructore::findOrFail($update["id"]);
        
		$cursos_instructore->id = $update['id'];
		$cursos_instructore->id_curso = $update['id_curso'];
		$cursos_instructore->id_instructor = $update['id_instructor'];

        $cursos_instructore->save();
        return response()->json(
            ["result"=> true],201);
    }
    public function destroy($id)
    {
        $cursos_instructore = CursosInstructore::findOrFail($id);
        $cursos_instructore->delete();
        return response()->json([],204);

    }
}