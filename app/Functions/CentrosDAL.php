<?php

namespace  App\Functions;

use App\Models\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentrosDAL
{

    public static function getAllCentros()
    {

        $centros = Centro::
        join('catalogos_detalles as departamento', 'centros.id_departamento', 'departamento.id')
        ->join('catalogos_detalles as municipio', 'centros.id_municipio', 'municipio.id')
        ->join('catalogos_detalles as pais', 'centros.id_pais', 'pais.id')

        ->where([
            ['deleted_at', '=', null]
        ])
        ->selectRaw("
        1 as contador,
        centros.id,
                centros.nombre,
                centros.descripcion,
                centros.contacto_nombre,
                centros.contacto_telefono,
                centros.contacto_correo,
                centros.quienes_somos,
                centros.mision,
                centros.vision,
                centros.valores,
                centros.direccion,
                centros.latitud,
                centros.longitud,
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
                departamento.nombre as departamento,
                municipio.nombre as municipio,
                pais.nombre as pais", [])
        ->get();
        
        foreach($centros as $key => $centro){
            $centros[$key]->contador = (int) $centro->contador;
            $centros[$key]->id = (int) $centro->id;
            $centros[$key]->latitud = (int) $centro->latitud;
            $centros[$key]->longitud = (int) $centro->longitud;
            $centros[$key]->computadoras = (int) $centro->computadoras;
            $centros[$key]->tablets = (int) $centro->tablets;
            $centros[$key]->celulares = (int) $centro->celulares;
        }
    return $centros;
    }
}