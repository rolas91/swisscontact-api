<?php

namespace App\Http\Controllers;

use Carbon;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Exports\BitacoraExport;
use App\Functions\BitacoraHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class BitacoraController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;

        $bitacora = Bitacora::
            join('usuarios', 'bitacora.user_id', 'usuarios.id')
            ->selectRaw("bitacora.id,bitacora.user_id,usuarios.nombre as usuario,bitacora.action,bitacora.model,bitacora.id_model,bitacora.ip_address,bitacora.user_agent,bitacora.url,bitacora.updated_at", [])
            ->orderBy('id', 'desc')
               ->paginate($rowsPerPage, ['*'], 'Page', $page);
        
    
        return response()->json(["bitacora" => $bitacora], 200);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //Validate inputs
        $bitacora = $request["bitacora"];
        $validator = Validator::make(
            $bitacora,
            [
                'user_id' => 'numeric',
                'action' => 'required|max:191',
                'model' => 'required|max:191',
                'id_model' => 'required|numeric',
                'ip_address' => 'max:191',
                'user_agent' => 'max:191',
                'url' => 'max:191',

            ]
        );
        $validator->validate();
        //$bitacora =BitacoraHelper::create();
        return response()->json(
            ['result' => true],
            200
        );
    }
    public function show($id)
    {
        $bitacora = Bitacora::findOrFail($id);
        return response()->json(["bitacora" =>  $bitacora], 200);
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request)
    {
        //Validate inputs
        $bitacora = $request["bitacora"];
        $validator = Validator::make(
            $bitacora,
            [
                'user_id' => 'numeric',
                'action' => 'required|max:191',
                'model' => 'required|max:191',
                'id_model' => 'required|numeric',
                'ip_address' => 'max:191',
                'user_agent' => 'max:191',
                'url' => 'max:191',

            ]
        );
        $validator->validate();
        $update = $request["bitacora"];
        $bitacora = Bitacora::findOrFail($update["id"]);

        $bitacora->id = $update['id'];
        $bitacora->user_id = $update['user_id'];
        $bitacora->action = $update['action'];
        $bitacora->model = $update['model'];
        $bitacora->id_model = $update['id_model'];
        $bitacora->ip_address = $update['ip_address'];
        $bitacora->user_agent = $update['user_agent'];
        $bitacora->url = $update['url'];

        $bitacora->save();
        return response()->json(
            ["result" => true],
            201
        );
    }
    public function destroy($id)
    {
        $bitacora = Bitacora::findOrFail($id);
        $bitacora->delete();
        return response()->json([], 204);
    }

    public function DescargarExcel(Request $request)
    {
        $export = new BitacoraExport();
        $log = new BitacoraHelper();
        $log->log($request, 'Exporta bitacora a excel', 'Bitacora', null);
        return $export->download('bitacora.xlsx');
    }
}
