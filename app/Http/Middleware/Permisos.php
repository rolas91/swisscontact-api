<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\RolesAcceso;

class Permisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $field, $id_acceso)
    {
        //Si viene del mobile dejamos pasar la request
        if ($request->has('mobile') && $request->mobile) {
            return $next($request);
        }
        
        $PermissionRole =  RolesAcceso::where([
                ['id_acceso',$id_acceso],
                ['id_rol',$request->user()->id_rol],
                [$field,true]
        ])->get();
        if (count($PermissionRole)>0) {
            return $next($request);
        } else {
            return response()->json(['message'=>'Lo sentimos, No tienes permiso de realizar esta acción...'], 401);
        }
        // return $next($request);
    }
}
