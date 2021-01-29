<?php

namespace  App\Functions;

use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class BitacoraHelper
{

    public function Store($bitacora)
    {
        $bitacora = Bitacora::create([
            'user_id' => $bitacora['user_id'],
            'action' => $bitacora['action'],
            'model' => $bitacora['model'],
            'id_model' => $bitacora['id_model'],
            'ip_address' => $bitacora['ip_address'],
            'user_agent' => $bitacora['user_agent'],
            'url' => $bitacora['url'],
        ]);

        return $bitacora;
    }

    public function Log(Request $request,$action,$model,$id_model)
    {
        $ip_address   = $this->resolve_ip($request);
        $url   = $this->resolve_url($request);
        $user_agent   = $this->resolve_agent($request);
        $user_id = $request->user()?  $request->user()->id : null;

        $bitacora = Bitacora::create([
            'user_id' => $user_id,
            'action' => $action,
            'model' => $model,
            'id_model' => $id_model,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'url' => $url,
        ]);

        return $bitacora;
    }


    public function resolve_url(Request $request): string
    {
        if (App::runningInConsole()) {
            return 'console';
        }

        // Just the full URL without query strings
        return $request->url();
    }

    public function resolve_ip(Request $request): string
    {
        return $request->ip();
    }

    public function resolve_agent(Request $request)
    {
        // Default to "N/A" if the User Agent isn't available
        return $request->header('User-Agent', 'N/A');
    }
}
