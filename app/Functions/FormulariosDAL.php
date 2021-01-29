<?php

namespace  App\Functions;

use App\Models\Formulario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormulariosDAL
{
    public static function getRespuestasFormulario($id_formulario=null, $id_centros =null, $page=1, $rowsPerPage=20, $filtro ='', $descargar = true)
    {
        $formulario = Formulario::
            join('catalogos_detalles as modo', 'formularios.id_modo', 'modo.id')
            ->where([
                ['formularios.id', '=', $id_formulario]
            ])->first();
        if($descargar AND $formulario->id_modo <> 5603){

            $data=collect(DB::select("call sp_respuestas_formulario(?,?,?,?,?)", [$page, $rowsPerPage, $id_formulario,  $id_centros,  $filtro]));
            $new_data = $data->transform(function($i){
                unset($i->centro);
                unset($i->id_formulario);
                unset($i->id_respuesta);
                unset($i->curso);
                unset($i->nota);
                unset($i->correo_participante);
                unset($i->telefono_participante);
                unset($i->cedula_participante);
                unset($i->participante_menor_edad);
                unset($i->fecha_nacimiento_participante);
                unset($i->sexo_participante);
                unset($i->salario_participante);
                unset($i->estado_civil_participante);
                unset($i->_edad);
                unset($i->contador);
                unset($i->costo);
                unset($i->rango_edad);
                unset($i->total_rows);
                
                return $i;
            });
            return $new_data;
            
        }else{
            return collect(DB::select("call sp_respuestas_formulario(?,?,?,?,?)", [$page, $rowsPerPage, $id_formulario,  $id_centros,  $filtro]));
        }
        
        Log::error('data:'.\json_encode($formulario));
    }
}
