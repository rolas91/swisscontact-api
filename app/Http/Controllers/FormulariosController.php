<?php

namespace App\Http\Controllers;

use stdClass;
use Carbon\Carbon;
use App\Models\Centro;
use App\Functions\Emails;
use App\Models\Formulario;
use App\Functions\Usuarios;
use App\Functions\CursosDAL;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Exports\CursosExport;
use App\Functions\CentrosDAL;
use App\Functions\HollandDAL;
use App\Exports\CentrosExport;
use App\Functions\formularios;
use App\Models\UsuariosCentro;
use App\Models\CursosMatricula;
use App\Models\FormulariosCampo;
use App\Functions\BitacoraHelper;
use App\Functions\FormulariosDAL;
use App\Functions\InstructoresDAL;
use Illuminate\Support\Facades\DB;
use App\Exports\InstructoresExport;
use App\Exports\TestsHollandExport;
use App\Functions\ParticipantesDAL;
use App\Models\FormulariosSeccione;
use Illuminate\Support\Facades\Log;
use App\Exports\ParticipantesExport;
use App\Models\FormulariosRespuesta;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\FormulariosRespuestasCampo;
use App\Exports\FormularioRespuestasExport;

class FormulariosController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page == 0 ? 1 : $request->page;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;

        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        $formularios = Formulario::
            join('catalogos_detalles as tema', 'formularios.id_tema', 'tema.id')
            ->join('catalogos_detalles as tipo', 'formularios.id_tipo', 'tipo.id')
            ->leftJoin('catalogos_detalles as modos', 'formularios.id_modo', 'modos.id')
            ->leftJoin('usuarios as usuario_creacion', 'formularios.id_usuario_creacion', 'usuario_creacion.id')
            ->leftJoin('usuarios as usuario_modificacion', 'formularios.id_usuario_modificacion', 'usuario_modificacion.id')

            ->where([
                ['formularios.deleted_at', '=', null]
            ])
            ->whereRaw("formularios.nombre like '%$request->filtro%'
			AND (usuario_creacion.id_rol =1 or usuario_creacion.id_rol=2 
			or exists (select 1 from usuarios_centros 
			where usuarios_centros.id_usuario =formularios.id_usuario_creacion 
            and  id_centro in ($id_centros))  ) ", [])
            ->orderBy('id', 'desc')
            ->selectRaw('formularios.id,
					formularios.id_tipo,
					formularios.id_tema,
					formularios.nombre,
					formularios.url,
                    formularios.id_modo,
                    usuario_creacion.id_rol as rol,
					modos.nombre as modo,
					DATE_FORMAT(formularios.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(formularios.fecha_fin, "%d/%m/%Y") as fecha_fin,
					(select count(1) from formularios_respuestas where id_formulario = formularios.id) as cantidad_respuestas,
					formularios.duracion,
					formularios.nota_maxima,
					formularios.ordenar_aleatoriamente,
					formularios.id_usuario_creacion,
					formularios.id_usuario_modificacion,
					tema.nombre as tema,
					tipo.nombre as tipo,
					usuario_creacion.nombre as usuario_creacion,
					usuario_modificacion.nombre as usuario_modificacion', [])
               ->paginate($rowsPerPage, ['*'], 'Page', $page);
      
       
        return response()->json(["formularios" => $formularios], 200);
    }
    public function create()
    {
        //
    }
    
    public function InicializarFormularioRespuesta(Request $request)
    {
        $slug =  $request['formulario']['url'];
        $formulario  =$request['formulario'];
        $form = FormulariosRespuesta::create([
             'id_formulario'  => $formulario['id'],
            'id_participante' => null,
            'id_evaluador' =>null,
            'fecha_inicio' => Carbon::parse($request['fecha_inicio']),
            'fecha_fin' => null,
            'duracion' => 0,
            'nota' => 0,
            'id_centro' => $request['id_centro'],
            'id_curso' => $request['id_curso'],
            'nombre_participante' => $formulario['nombre_participante'],
            'correo_participante' => $formulario['correo_participante'],
            'slug' => str_random(16)
        ]);
        
        return response()->json(['form'=> $form], 200);
    }
    
    public function CargarFormularioRespuesta(Request $request)
    {
        $slug =  $request['formulario']['url'];
        $slug_respuesta = $request[ 'slug_respuesta'];
        $form = FormulariosRespuesta::where('slug', $slug_respuesta)->first();
        return response()->json(['form'=> $form], 200);
    }


    public function store(Request $request)
    {
        //Validate inputs
        $formulario = json_decode($request["formulario"]);
        $reglas = $request['reglas'];

        $validator = Validator::make(
            (array) $formulario,
            [
                'id_tipo' => 'required|numeric',
                'id_tema' => 'numeric',
                'nombre' => 'required|max:250',
                'titulo' => 'max:500',
                'descripcion' => 'max:65535',
                'url' => 'required|max:500',
            ]
        );
        $validator->validate();
        try {
            DB::beginTransaction();
            $secciones = $formulario->secciones;

            
            //Validamos que no exista otro formulario activo con el mismo nombre
            $form_same_name = Formulario::where('nombre', $formulario->nombre)->first();
            if ($form_same_name) {
                throw new \Exception("Ya existe un formulario con el mismo nombre");
            }



            $form = Formulario::create([
                'id_tipo' => $formulario->id_tipo,
                'id_tema' => $formulario->id_tema,
                'nombre' => $formulario->nombre,
                'url' => $formulario->url,
                'duracion' => $formulario->duracion,
                'nota_maxima' => $formulario->nota_maxima,
                'ordenar_aleatoriamente' => $formulario->ordenar_aleatoriamente,
                'id_usuario_creacion' =>  $request->user()->id,
                'fecha_inicio' => Carbon::createFromFormat('d/m/Y', $formulario->fecha_inicio),
                'fecha_fin' => Carbon::createFromFormat('d/m/Y', $formulario->fecha_fin),
                'id_usuario_modificacion' => null,
                'reglas' => $reglas,
                'abierto' => $formulario->id_modo,
            ]);

            $map =new stdClass();
            $dict =[];


            foreach ($secciones as $key => $seccion) {
                $form_secciones = FormulariosSeccione::create([
                    'id_formulario' => $form->id,
                    'titulo' => $seccion->titulo,
                    'descripcion' => $seccion->descripcion
                ]);

                
                
                $campos = $seccion->campos;
                
                foreach ($campos as $key => $_campo) {

                    $respuesta_correcta = "";
                    if ($form->id_tipo == 5561) {
                        $respuesta_correcta = ($_campo->respuesta_correcta ? (gettype($_campo->respuesta_correcta) == 'object' ? $_campo->respuesta_correcta->nombre : $_campo->respuesta_correcta) : null);

                        //si es de tipo tabla
                        if ($_campo->id_tipo == 3) {
                            $respuesta_correcta = "";
                            foreach ($_campo->arregloTable as $key => $row) {

                                foreach ($row->columns as $key2 => $column) {
                                    if (((int)$column->nota) > 0) {
                                        $respuesta_correcta .= $row->valor . ":" . $column->valor . ",";
                                    }
                                }
                            }
                            $respuesta_correcta = mb_substr($respuesta_correcta, 0, -1);
                        }

                        //tipo de opciones
                        if ($_campo->id_tipo == 1 && $_campo->tipo_input->nombre == "Casillas de verificación") {
                            $respuesta_correcta = "";
                            foreach ($_campo->opciones as $key => $opcion) {
                                if ($opcion->nota && $opcion->nota != "" && $opcion->nota > 0) {
                                    $respuesta_correcta .= $opcion->nombre . ",";
                                }
                            }
                            $respuesta_correcta = mb_substr($respuesta_correcta, 0, -1);
                        }
                    }

                   


                    $campo =	FormulariosCampo::create([
                        'id_formulario' => $form->id,
                        'id_seccion' => $form_secciones->id,
                        'id_tipo' => $_campo->id_tipo,
                        'texto' => $_campo->texto,
                        'requerido' => $_campo->requerido,
                        'tipo_input' => json_encode($_campo->tipo_input),
                        'opciones' => json_encode($_campo->opciones),
                        'nota' => $_campo->nota,
                        'editando' => $_campo->editando,
                        'temp' => $_campo->temp,
                        'minimo' => $_campo->minimo,
                        'maximo' => $_campo->maximo,
                        'subtitulo' => $_campo->subtitulo,
                        'respuesta_correcta' => $respuesta_correcta,
                        'arregloTable' => $_campo->id_tipo === 3 ? json_encode($_campo->arregloTable) : null
                    ]);

                  
                    //Hacemos esto para mapear los ids de la intefaz con los nuevos ids guardados en la base de datos
                    $map =new stdClass();
                    $map->seccion_old= $seccion->id;
                    $map->seccion_new= $form_secciones->id;
                    $map->campo_old= $_campo->id;
                    $map->campo_new= $campo->id;
                    array_push($dict, $map);

                    $url_imagen = "";
                    if ($request->hasFile('imagen' . $_campo->id)) {
                        $file      = $request->file('imagen' . $_campo->id);
                        $imagen   = 'imagen' . $_campo->id;
                        $file->move(public_path('img/formularios/'), $imagen);
                        $image_path = public_path('img/formularios/') . $campo->imagen;
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        $campo->imagen = $imagen;
                        $campo->save();
                        $url_imagen =  env('APP_URL') . '/img/formularios/' . $imagen;
                    }
                }
            }


            $_reglas = json_decode($request['reglas']);
            $reglas = $_reglas->reglas;

            foreach ($dict as $key => $map) {
                //Actualizamos los ids en los campos de las reglas
                foreach ($reglas as $key => $regla) {
                    if ($regla->seccion_excluyente === $map->seccion_old and  $regla->campo_excluyente === $map->campo_old) {
                        $reglas[$key]->campo_excluyente = $map->campo_new;
                    }
                    if ($regla->seccion_excluyente === $map->seccion_old and  $regla->campo_excluido === $map->campo_old) {
                        $reglas[$key]->campo_excluido = $map->campo_new;
                    }
                    if ($regla->seccion_excluida === $map->seccion_old and $regla->campo_excluyente === $map->campo_old) {
                        $reglas[$key]->campo_excluyente = $map->campo_new;
                    }
                    if ($regla->seccion_excluida === $map->seccion_old and $regla->campo_excluido === $map->campo_old) {
                        $reglas[$key]->campo_excluido = $map->campo_new;
                    }
                }
            }

            foreach ($dict as $key => $map) {
                //Actualizamos los ids en los campos de las reglas
                foreach ($reglas as $key => $regla) {
                    //actualizamos los ids en las secciones
                    if ($regla->seccion_excluyente === $map->seccion_old) {
                        $reglas[$key]->seccion_excluyente = $map->seccion_new;
                    }
                    if ($regla->seccion_excluida === $map->seccion_old) {
                        $reglas[$key]->seccion_excluida = $map->seccion_new;
                    }
                }
            }


            $_reglas->reglas = $reglas;
            $form->reglas = json_encode($_reglas);
            $form->update();

            $log = new BitacoraHelper();
            $log->log($request, 'Crea Formulario', 'Formularios', $form->id);

            DB::commit();
            return response()->json(
                ['result' => true],
                200
            );
        } catch (\Throwable $th) {
            DB::rollback();

            //return response()->json(['result' => false, 'message' => 'Ya existe un formulario con ese nombre, por favor ingrese otro nombre'], 500);
            throw $th;
        }
    }
    public function show($id)
    {
        $formulario = Formulario::findOrFail($id);
        return response()->json(["formulario" =>  $formulario], 200);
    }
    public function edit($id, Request $request)
    {
        $base64 = ($request->has('base64') && $request['base64']);
        $formulario = Formulario::
            join('catalogos_detalles as tema', 'formularios.id_tema', 'tema.id')
            ->join('catalogos_detalles as tipo', 'formularios.id_tipo', 'tipo.id')
            ->leftJoin('catalogos_detalles as modos', 'formularios.id_modo', 'modos.id')
            ->leftJoin('usuarios as usuario_creacion', 'formularios.id_usuario_creacion', 'usuario_creacion.id')
            ->leftJoin('usuarios as usuario_modificacion', 'formularios.id_usuario_modificacion', 'usuario_modificacion.id')

            ->where([
                ['formularios.deleted_at', '=', null],
                ['formularios.id', '=', $id]
            ])
            ->selectRaw('formularios.id,
					formularios.id_tipo,
					formularios.id_tema,
					formularios.nombre,
					formularios.url,
					formularios.id_modo,
					modos.nombre as modo,
					DATE_FORMAT(formularios.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(formularios.fecha_fin, "%d/%m/%Y") as fecha_fin,
					formularios.duracion,
					formularios.nota_maxima,
					formularios.ordenar_aleatoriamente,
					formularios.id_usuario_creacion,
					formularios.id_usuario_modificacion,
					tema.nombre as tema,
                    tipo.nombre as tipo,
                    formularios.reglas', [])
            ->first();

        $secciones = FormulariosSeccione::where('id_formulario', $formulario->id)->get();
        $campos = FormulariosCampo::where('id_formulario', $formulario->id)->get();

        if ($base64===true) {
            foreach ($campos as $key => $campo) {
                if (!isset($campo->imagen)) {
                    continue;
                }
                try {
                    $image =  env('APP_URL').'/img/formularios/'.$campo->imagen;
                    $imageData = base64_encode(file_get_contents($image));
                    $mimetype =File::extension($image);
                    // Format the image SRC:  data:{mime};base64,{data};
                    $src = 'data: ' . $mimetype . ';base64,' . $imageData;
                    // Echo out a sample image
                    $campos[$key]->imagen =  $src ;
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }

    
        return response()->json(['formulario' => $formulario, 'secciones' => $secciones, 'campos' => $campos]);
    }


    public function update(Request $request)
    {
        $formulario = json_decode($request['formulario']);
        $reglas = $request['reglas'];

        //Validate inputs
        $validator = Validator::make(
            (array) $formulario,
            [
                'id' => 'required',
                'id_tipo' => 'required|numeric',
                'nombre' => 'required|max:250',
                'titulo' => 'max:500',
                'descripcion' => 'max:65535',
                'url' => 'required|max:500',
                'fecha_inicio' => 'required',
                'fecha_fin' => 'required'

            ]
        );
        $validator->validate();

        //Validamos que no exista otro formulario activo con el mismo nombre
        $form_same_name = Formulario::where([
                ['nombre', $formulario->nombre],
                ['id', '<>', $formulario->id]
            ])->first();
            
        if ($form_same_name) {
            throw new \Exception("Ya existe un formulario con el mismo nombre");
        }


        try {
            DB::beginTransaction();
            $secciones = $formulario->secciones;

            $form = Formulario::findOrFail($formulario->id);
            $form->id = $formulario->id;
            $form->id_tipo = $formulario->id_tipo;
            $form->id_tema = $formulario->id_tema;
            $form->nombre = $formulario->nombre;
            $form->url = $formulario->url;
            $form->id_modo = $formulario->id_modo;
            $form->duracion = $formulario->duracion;
            $form->nota_maxima = $formulario->nota_maxima;
            $form->ordenar_aleatoriamente = $formulario->ordenar_aleatoriamente;
            $form->id_usuario_modificacion = $request->user()->id;
            $form->fecha_inicio = Carbon::createFromFormat('d/m/Y', $formulario->fecha_inicio);
            $form->fecha_fin = Carbon::createFromFormat('d/m/Y', $formulario->fecha_fin);
            $form->reglas = $reglas;
            $form->save();

            //Primero eliminamos las secciones que fueron eliminadas valga la redundancia XD
            $campos_a_eliminar = FormulariosCampo::where('id_formulario', $form->id)
                ->whereNotIn('id_seccion', collect($secciones)->pluck('id')->toArray());

            $ids_campos_a_eliminar = FormulariosCampo::where('id_formulario', $form->id)
                ->whereNotIn('id_seccion', collect($secciones)->pluck('id')->toArray())->get()->pluck('id')->toArray();

            $formulario_respuestas = FormulariosRespuesta::where('id_formulario', $form->id)->get();

            if ($formulario_respuestas) {
                foreach ($formulario_respuestas as $key => $respuesta) {
                    //Eliminamos las respuestas en caso que existan vinculadas a los campos de la seccion eliminada
                    FormulariosRespuestasCampo::
                where('id_formulario_respuesta', $respuesta->id)->
                whereIn('id_formulario_campo', $ids_campos_a_eliminar)->delete();
                }
            }

            //Eliminamos los campos vinculados a la seccion a eliminar
            $campos_a_eliminar->delete();

            //eliminamos la sección
            FormulariosSeccione::where('id_formulario', $form->id)
                ->whereNotIn('id', collect($secciones)->pluck('id')->toArray())->delete();

            //Eliminamos las imagenes de los campos a eliminar
            foreach ($campos_a_eliminar as $key => $del_campo) {
                File::delete(public_path('img/formularios/') . $del_campo->imagen);
            }


            foreach ($secciones as $key => $seccion) {
                $campos_a_eliminar = FormulariosCampo::where('id_formulario', $form->id)
                    ->where('id_seccion', $seccion->id)
                ->whereNotIn('id', collect($seccion->campos)->pluck('id')->toArray());


                //eliminamos los campos que fueron eliminados desde la interfaz
                $campos_a_eliminar->delete();

                //Modificamos las secciones que se mantienen
                $form_seccion=  FormulariosSeccione::where('id_formulario', $form->id)
                ->where('id', $seccion->id)->first();
                if ($form_seccion) {
                    $form_seccion->titulo = $seccion->titulo;
                    $form_seccion->descripcion = $seccion->descripcion;
                    $form_seccion->save();
                }

        
                //por último agregamos secciones nuevas
                if (!$form_seccion) {
                    $form_seccion = FormulariosSeccione::create([
                        'id_formulario' => $form->id,
                        'titulo' => $seccion->titulo,
                        'descripcion' => $seccion->descripcion
                    ]);
                }

                $map =new stdClass();
                $dict =[];

                $campos = $seccion->campos;
                foreach ($campos as $key => $_campo) {
                    $old_campo = FormulariosCampo::where('id_formulario', $form->id)
                    ->where('id_seccion', $seccion->id)
                    ->where('id', $_campo->id)->first();
                    //Si la imagen la eliminamos procedemos a elminarla del servidor
                    if ($old_campo && $_campo && $_campo->imagen_url === null) {
                        //$old_campo = $old_campos->where('id', $_campo->id)->first();
                        File::delete(public_path('img/formularios/') . $old_campo->imagen);
                    }


                    $respuesta_correcta = "";
                    if( $form->id_tipo == 5561){
                        $respuesta_correcta = ($_campo->respuesta_correcta ? (gettype($_campo->respuesta_correcta) == 'object' ? $_campo->respuesta_correcta->nombre : $_campo->respuesta_correcta) : null);

                        //si es de tipo tabla
                        if ($_campo->id_tipo == 3) {
                            $respuesta_correcta = "";
                            foreach ($_campo->arregloTable as $key => $row) {

                                foreach ($row->columns as $key2 => $column) {
                                    if (((int)$column->nota) > 0) {
                                        $respuesta_correcta .= $row->valor . ":" . $column->valor . ",";
                                    }
                                }
                            }
                            $respuesta_correcta = mb_substr($respuesta_correcta, 0, -1);
                        }

                        //tipo de opciones
                        if ($_campo->id_tipo == 1 && $_campo->tipo_input->nombre == "Casillas de verificación") {
                            $respuesta_correcta = "";
                            foreach ($_campo->opciones as $key => $opcion) {
                                if ($opcion->nota && $opcion->nota != "" && $opcion->nota > 0) {
                                    $respuesta_correcta .= $opcion->nombre . ",";
                                }
                            }
                            $respuesta_correcta = mb_substr($respuesta_correcta, 0, -1);
                        }
                    }


                   
                   


                    //actualizamos el campo existente
                    if ($old_campo) {
                        $old_campo->id_tipo = $_campo->id_tipo;
                        $old_campo->texto = $_campo->texto;
                        $old_campo->requerido = $_campo->requerido;
                        $old_campo->tipo_input = json_encode($_campo->tipo_input);
                        $old_campo->opciones = json_encode($_campo->opciones);
                        $old_campo->nota = $_campo->nota;
                        $old_campo->editando = $_campo->editando;
                        $old_campo->temp = $_campo->temp;
                        $old_campo->imagen = $_campo->imagen_url;
                        $old_campo->minimo = $_campo->minimo;
                        $old_campo->maximo = $_campo->maximo;
                        $old_campo->respuesta_correcta = $respuesta_correcta;
                        $old_campo->arregloTable = $_campo->id_tipo === 3 ? json_encode($_campo->arregloTable) : null;
                        $old_campo->subtitulo = $_campo->subtitulo;
                        $old_campo->save();


                        //Si agrega una nueva imagen 
                        $url_imagen = "";
                        if ($request->hasFile('imagen' . $_campo->id)) {
                            $file      = $request->file('imagen' . $_campo->id);
                            $imagen   = 'imagen' . $old_campo->id;
                            $file->move(public_path('img/formularios/'), $imagen);
                            $image_path = public_path('img/formularios/') . $old_campo->imagen;
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $old_campo->imagen = $imagen;
                            $old_campo->save();
                            $url_imagen =  env('APP_URL') . '/img/formularios/' . $imagen;
                        }
                    }
                    
                    
                    //Si no existe lo creamos
                    if (!$old_campo) {
                        $campo =	FormulariosCampo::create([
                        'id_formulario' => $form->id,
                        'id_seccion' => $form_seccion->id,
                        'id_tipo' => $_campo->id_tipo,
                        'texto' => $_campo->texto,
                        'requerido' => $_campo->requerido,
                        'tipo_input' => json_encode($_campo->tipo_input),
                        'opciones' => json_encode($_campo->opciones),
                        'nota' => $_campo->nota,
                        'editando' => $_campo->editando,
                        'temp' => $_campo->temp,
                        'imagen' => $_campo->imagen_url,
                        'minimo' => $_campo->minimo,
                        'maximo' => $_campo->maximo,
                        'respuesta_correcta' => $_campo->respuesta_correcta ? gettype($_campo->respuesta_correcta) === 'object' ? $_campo->respuesta_correcta->nombre : $_campo->respuesta_correcta : null,
                        'arregloTable' => $_campo->id_tipo === 3 ? json_encode($_campo->arregloTable) : null,
                        'subtitulo' => $_campo->subtitulo
                    ]);


                        //Hacemos esto para mapear los ids de la intefaz con los nuevos ids guardados en la base de datos
                        $map =new stdClass();
                        $map->seccion_old= $seccion->id;
                        $map->seccion_new= $form_seccion->id;
                        $map->campo_old= $_campo->id;
                        $map->campo_new= $campo->id;
                        array_push($dict, $map);


                        $url_imagen = "";
                        if ($request->hasFile('imagen' . $_campo->id)) {
                            $file      = $request->file('imagen' . $_campo->id);
                            $imagen   = 'imagen' . $campo->id;
                            $file->move(public_path('img/formularios/'), $imagen);
                            $image_path = public_path('img/formularios/') . $campo->imagen;
                            if (File::exists($image_path)) {
                                File::delete($image_path);
                            }
                            $campo->imagen = $imagen;
                            $campo->save();
                            $url_imagen =  env('APP_URL') . '/img/formularios/' . $imagen;
                        }
                    }
                }
            }

             $_reglas = json_decode($form->reglas);
            $reglas = $_reglas->reglas;

            foreach ($dict as $key => $map) {
                //Actualizamos los ids en los campos de las reglas
                foreach ($reglas as $key => $regla) {
                    if ($regla->seccion_excluyente === $map->seccion_old and  $regla->campo_excluyente === $map->campo_old) {
                        $reglas[$key]->campo_excluyente = $map->campo_new;
                    }
                    if ($regla->seccion_excluyente === $map->seccion_old and  $regla->campo_excluido === $map->campo_old) {
                        $reglas[$key]->campo_excluido = $map->campo_new;
                    }
                    if ($regla->seccion_excluida === $map->seccion_old and $regla->campo_excluyente === $map->campo_old) {
                        $reglas[$key]->campo_excluyente = $map->campo_new;
                    }
                    if ($regla->seccion_excluida === $map->seccion_old and $regla->campo_excluido === $map->campo_old) {
                        $reglas[$key]->campo_excluido = $map->campo_new;
                    }
                }
            }

              foreach ($dict as $key => $map) {
                  //Actualizamos los ids en los campos de las reglas
                  foreach ($reglas as $key => $regla) {
                      //actualizamos los ids en las secciones
                      if ($regla->seccion_excluyente === $map->seccion_old) {
                          $reglas[$key]->seccion_excluyente = $map->seccion_new;
                      }
                      if ($regla->seccion_excluida === $map->seccion_old) {
                          $reglas[$key]->seccion_excluida = $map->seccion_new;
                      }
                  }
              }


            $_reglas->reglas = $reglas;
            $form->reglas = json_encode($_reglas);
            $form->update();


            $log = new BitacoraHelper();
            $log->log($request, 'Actualiza Formulario', 'Formularios', $formulario->id);

            DB::commit();
            return response()->json(
                ["result" => true],
                201
            );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
    public function destroy(Request $request, $id)
    {
        $formulario = Formulario::findOrFail($id);
        $formulario->delete();

        $log = new BitacoraHelper();
        $log->log($request, 'Elmina Formulario', 'Formularios', $formulario->id);
        return response()->json([], 204);
    }

    public function getFormulario($slug)
    {
        $formulario = Formulario::
            join('catalogos_detalles as tema', 'formularios.id_tema', 'tema.id')
            ->join('catalogos_detalles as tipo', 'formularios.id_tipo', 'tipo.id')
            ->join('catalogos_detalles as modos', 'formularios.id_modo', 'modos.id')
            ->leftJoin('usuarios as usuario_creacion', 'formularios.id_usuario_creacion', 'usuario_creacion.id')
            ->leftJoin('usuarios as usuario_modificacion', 'formularios.id_usuario_modificacion', 'usuario_modificacion.id')

            ->where([
                ['formularios.deleted_at', '=', null],
                ['formularios.url', '=', $slug]
            ])
            ->whereRaw('datediff(formularios.fecha_fin,now()) >=0')
            ->selectRaw('formularios.id,
					formularios.id_tipo,
					formularios.id_tema,
					formularios.nombre,
					formularios.url,
					formularios.id_modo,
					formularios.ordenar_aleatoriamente,
					modos.nombre as modo,
					\'\' as nombre_participante,
					\'\'as correo_participante,
					DATE_FORMAT(formularios.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(formularios.fecha_fin, "%d/%m/%Y") as fecha_fin,
					formularios.duracion,
					formularios.nota_maxima,
					formularios.id_usuario_creacion,
                    formularios.id_usuario_modificacion,
                    formularios.reglas,
					tema.nombre as tema,
					tipo.nombre as tipo', [])
            ->first();


        //Si no se encontró el formulario
        if (!$formulario) {
            return response()->json(['result'=> false,'message' => 'El formulario ya no está disponible']);
        }

        $secciones = FormulariosSeccione::where('id_formulario', $formulario->id)->get();
        $campos = FormulariosCampo::where('id_formulario', $formulario->id)->get();



        foreach ($campos as $key => $campo) {
            if (!isset($campo->imagen)) {
                continue;
            }
            $image =  env('APP_URL').'/img/formularios/'.$campo->imagen;
            try {
                $imageData = base64_encode(file_get_contents($image));
                $mimetype =File::extension($image);
                // Format the image SRC:  data:{mime};base64,{data};
                $src = 'data: ' . $mimetype . ';base64,' . $imageData;
                // Echo out a sample image
                $campos[$key]->imagen =  $src ;
            } catch (\Throwable $th) {
                //throw $th;
            }
        }




        $formulario->ordenar_aleatoriamente = $formulario->ordenar_aleatoriamente === 1;
        return response()->json([ 'resultado' => true, 'formulario' => $formulario, 'secciones' => $secciones, 'campos' => $campos]);
    }

    public function getFormularioRespuesta($id_formulario, $id_formulario_respuesta)
    {
        $formulario = Formulario::
            join('catalogos_detalles as tema', 'formularios.id_tema', 'tema.id')
            ->join('catalogos_detalles as tipo', 'formularios.id_tipo', 'tipo.id')
            ->join('catalogos_detalles as modos', 'formularios.id_modo', 'modos.id')
            ->leftJoin('usuarios as usuario_creacion', 'formularios.id_usuario_creacion', 'usuario_creacion.id')
            ->leftJoin('usuarios as usuario_modificacion', 'formularios.id_usuario_modificacion', 'usuario_modificacion.id')

            ->where([
                ['formularios.deleted_at', '=', null],
                ['formularios.id', '=', $id_formulario]
            ])
            ->selectRaw('formularios.id,
					formularios.id_tipo,
					formularios.id_tema,
					formularios.nombre,
					formularios.url,
					formularios.id_modo,
					modos.nombre as modo,
					DATE_FORMAT(formularios.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(formularios.fecha_fin, "%d/%m/%Y") as fecha_fin,
					formularios.duracion,
					formularios.nota_maxima,
					formularios.id_usuario_creacion,
                    formularios.id_usuario_modificacion,
                    formularios.reglas,
					tema.nombre as tema,
					tipo.nombre as tipo', [])
            ->first();

        $secciones = FormulariosSeccione::where('id_formulario', $formulario->id)->get();
        $campos = FormulariosCampo::where('id_formulario', $formulario->id)
            ->selectRaw("nombre,texto,id_seccion,id_tipo,tipo_input,opciones,nota,imagen,arregloTable, (select valor from formularios_respuestas_campos where id_formulario_respuesta=$id_formulario_respuesta and formularios_respuestas_campos.id_formulario_campo = formularios_campos.id  ) as respuesta ", [])
            ->get();


        foreach ($campos as $key => $campo) {
            if (!isset($campo->imagen)) {
                continue;
            }
            $image =  env('APP_URL').'/img/formularios/'.$campo->imagen;
            // Read image path, convert to base64 encoding
            $imageData = base64_encode(file_get_contents($image));
            $mimetype =File::extension($image);
            
            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data: ' . $mimetype . ';base64,' . $imageData;
            // Echo out a sample image
            $campos[$key]->imagen =  $src ;
        }

        $respuesta = FormulariosRespuesta::find($id_formulario_respuesta);
        $respuesta_campos = FormulariosRespuestasCampo::where('id_formulario_respuesta', $id_formulario_respuesta)->get();

        return response()->json(['formulario' => $formulario, 'secciones' => $secciones, 'campos' => $campos, 'respuesta' => $respuesta, 'respuesta_campos' =>  $respuesta_campos]);
    }

    public function getFormularioRespuestaEvaluar($id_formulario, $id_formulario_respuesta)
    {
        $formulario = DB::table('formularios')
            ->join('catalogos_detalles as tema', 'formularios.id_tema', 'tema.id')
            ->join('catalogos_detalles as tipo', 'formularios.id_tipo', 'tipo.id')
            ->join('catalogos_detalles as modos', 'formularios.id_modo', 'modos.id')
            ->leftJoin('usuarios as usuario_creacion', 'formularios.id_usuario_creacion', 'usuario_creacion.id')
            ->leftJoin('usuarios as usuario_modificacion', 'formularios.id_usuario_modificacion', 'usuario_modificacion.id')

            ->where([
                ['formularios.deleted_at', '=', null],
                ['formularios.id', '=', $id_formulario]
            ])
            ->selectRaw('formularios.id,
					formularios.id_tipo,
					formularios.id_tema,
					formularios.nombre,
					formularios.url,
					formularios.id_modo,
					modos.nombre as modo,
					DATE_FORMAT(formularios.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(formularios.fecha_fin, "%d/%m/%Y") as fecha_fin,
					formularios.duracion,
					formularios.nota_maxima,
					formularios.id_usuario_creacion,
                    formularios.id_usuario_modificacion,

					tema.nombre as tema,
					tipo.nombre as tipo', [])
            ->first();

        $secciones = FormulariosSeccione::where('id_formulario', $formulario->id)->get();
        $campos = FormulariosCampo::where('id_formulario', $formulario->id)
            ->selectRaw("id as id_formulario_campo,nombre,texto,id_seccion,id_tipo,tipo_input,opciones,nota,imagen,respuesta_correcta,arregloTable, (select valor from formularios_respuestas_campos where id_formulario_respuesta=$id_formulario_respuesta and formularios_respuestas_campos.id_formulario_campo = formularios_campos.id  ) as respuesta ", [])
            ->orderBy('nota', 'desc')
            ->get();

        $formulario_respuesta = FormulariosRespuesta::where(
            [
                ['id_formulario', $formulario->id],
                ['id', $id_formulario_respuesta]
            ]
        )->first();

        $respuesta_campos = FormulariosRespuestasCampo::where('id_formulario_respuesta', $formulario_respuesta->id)->get();


        return response()->json(['formulario' => $formulario, 'secciones' => $secciones, 'campos' => $campos, 'formulario_respuesta' => $formulario_respuesta, 'respuesta_campos' => $respuesta_campos]);
    }

    public function imprimirFormulario($id_formulario)
    {
        $formulario = Formulario::
            join('catalogos_detalles as tema', 'formularios.id_tema', 'tema.id')
            ->join('catalogos_detalles as tipo', 'formularios.id_tipo', 'tipo.id')
            ->join('catalogos_detalles as modos', 'formularios.id_modo', 'modos.id')
            ->leftJoin('usuarios as usuario_creacion', 'formularios.id_usuario_creacion', 'usuario_creacion.id')
            ->leftJoin('usuarios as usuario_modificacion', 'formularios.id_usuario_modificacion', 'usuario_modificacion.id')

            ->where([
                ['formularios.deleted_at', '=', null],
                ['formularios.id', '=', $id_formulario]
            ])
            ->selectRaw('formularios.id,
					formularios.id_tipo,
					formularios.id_tema,
					formularios.nombre,
					formularios.url,
					formularios.id_modo,
					modos.nombre as modo,
					DATE_FORMAT(formularios.fecha_inicio, "%d/%m/%Y") as fecha_inicio,
					DATE_FORMAT(formularios.fecha_fin, "%d/%m/%Y") as fecha_fin,
					formularios.duracion,
					formularios.nota_maxima,
					formularios.id_usuario_creacion,
                    formularios.id_usuario_modificacion,
                    formulario.reglas,
					tema.nombre as tema,
					tipo.nombre as tipo', [])
            ->first();

        $secciones = FormulariosSeccione::where('id_formulario', $formulario->id)->get();
        $campos = FormulariosCampo::where('id_formulario', $formulario->id)
            ->selectRaw("nombre,texto,id_seccion,id_tipo,tipo_input,opciones,nota, null as respuesta,imagen,arregloTable ", [])
            ->get();

        foreach ($campos as $key => $campo) {
            // tipo de campo valoracion
            if ($campo->id_tipo===6) {
                $campo->respuesta =10;
            }


            if (!isset($campo->imagen)) {
                continue;
            }
            $image =  env('APP_URL').'/img/formularios/'.$campo->imagen;
            // Read image path, convert to base64 encoding
            $imageData = base64_encode(file_get_contents($image));
            $mimetype =File::extension($image);
                
            // Format the image SRC:  data:{mime};base64,{data};
            $src = 'data: ' . $mimetype . ';base64,' . $imageData;
            // Echo out a sample image
            $campos[$key]->imagen =  $src ;
        }


        return response()->json(['formulario' => $formulario, 'secciones' => $secciones, 'campos' => $campos]);
    }



    public function responderFormulario(Request $request)
    {
        $formulario = $request['formulario'];
        $secciones = $formulario['secciones'];
        $id_centro = $request['id_centro'];
        $id_curso = $request['id_curso'];
        $nombre_participante = $request['nombre_participante'];
        $correo_particiopante = $request['correo_participante'];
        //Cedula, Correo, Telefono
        $tipo_identidad = $request['tipo_identidad'];
        //$doc_identidad = $request['doc_identidad'];
        $fecha_inicio = Carbon::parse($request['fecha_inicio']);
        $fecha_fin = Carbon::now();
        $nota = 0;


        // Si es modo abierto o anonimo no obtenemos el participante
        if (!($formulario['id_modo'] == 5604 || $formulario['id_modo'] == 5605)) {
            $participante = Participante::where('id', $formulario['participante']['id_participante'])->first();

            //Si no encontramos al participante salimos
            if (!$participante) {
                return response()->json(['message' => "No se encontró ningún participante con el $tipo_identidad proporcionado"], 422);
            }
        }

        try {
            DB::beginTransaction();

            $formulario_respuesta = FormulariosRespuesta::create([
                'id_formulario' => $formulario['id'],
                'id_centro' => $id_centro,
                'id_curso' => $id_curso,
                'id_participante' => ($formulario['id_modo'] == 5604 || $formulario['id_modo'] == 5605) ? null : $participante->id,
                'nombre_participante' => $formulario['id_modo'] == 5604 ? $formulario['nombre_participante'] : null,
                'correo_participante' => $formulario['id_modo'] == 5604 ? $formulario['correo_participante'] : null,
                'id_evaluador' => null,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'nota' => $nota
            ]);

            foreach ($secciones as $key => $seccion) {
                $campos = $seccion['campos'];
                foreach ($campos as $key => $campo) {
                    $nota_campo = 0;
                    //Si es de seleccion multiple (select o radio)
                    if($campo['id_tipo'] == 1 && ( $campo['tipo_input']['id'] == 1 || $campo['tipo_input']['id'] == 3) ){

                            if($campo['respuesta_correcta'] == $campo['respuesta']){
                                $nota_campo +=
                            (int) $campo['nota'];
                                $nota += (int) $campo['nota'];
                            }
                    }



                    //si es casilla de verificacion
                    if ($campo['id_tipo'] == 1 && $campo['tipo_input']['id'] == 2) {
                        $respuesta_correcta="";
                        $respuestas = [];
                        foreach ($campo['opciones'] as $key => $opcion) {
                            if (isset($opcion['respuesta']) && $opcion['respuesta']) {
                                array_push($respuestas, $opcion['respuesta']);
                               
                                $nota_campo += (int)$opcion['nota'];
                                $nota += (int) $opcion['nota'];
                               
                            }
                        }
                      


                        FormulariosRespuestasCampo::create([
                            'id_formulario_respuesta' => $formulario_respuesta->id,
                            'id_formulario_campo' => $campo['id'],
                            'valor' => implode(",", $respuestas),
                            'nota' => $nota_campo
                        ]);
                    }
                    //Si es tipo tabla
                    elseif ($campo['id_tipo'] == 3) {
                        $respuestas = [];
                        
                        foreach ($campo['arregloTable']  as $key => $fila) {
                            foreach ($fila['columns'] as $key => $col) {
                                if ($col['checked']) {
                                    array_push($respuestas, [ $fila['valor'], $col['valor']]);
                                    $nota += (int) $col['nota'];
                                    $nota_campo += (int) $col['nota'];
                                }
                            }
                        }

                        FormulariosRespuestasCampo::create([
                            'id_formulario_respuesta' => $formulario_respuesta->id,
                            'id_formulario_campo' => $campo['id'],
                            'valor' => json_encode($respuestas),
                            'nota' => $nota_campo

                        ]);
                    } else {
                        if (isset($campo['respuesta'])) {
                            FormulariosRespuestasCampo::create([
                                'id_formulario_respuesta' => $formulario_respuesta->id,
                                'id_formulario_campo' => $campo['id'],
                                'valor' => $campo['respuesta'],
                                'nota' => $nota_campo
                            ]);
                        }
                    }
                }
            }

            $formulario_respuesta->nota = $nota;
            $formulario_respuesta->save();

            $log = new BitacoraHelper();
            $log->log($request, 'Responde Formulario', 'Formulario Respuesta', $formulario_respuesta->id);
            DB::commit();

            return response()->json(['message' => 'Formulario llenado correctamente'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json(['result' =>  false, 'message' => 'ha ocurrido un error'], 500);
        }
    }

   

    public function verificarIdentidad(Request $request)
    {
        return formularios::verficarIdentidad($request);
    }

    public function RespuestasFormulario(Request $request)
    {
        $id_formulario = (int) $request['id_formulario'];
        $table_format = $request->has('table_format');

        $page =  $request->has('page')?  ((int)$request->page -1) : 0 ;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999;

        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';


        //Primero validamos el tipo de reporte seleccionado
        //Si es tipo formulario
        if ($id_formulario < 10000) {
            try {
                $usuario = $request->user();
                $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());
                $filtro = $request->filtro;
                $respuestas = FormulariosDAL::getRespuestasFormulario($id_formulario, $id_centros, $page, $rowsPerPage, $filtro,false);
                $respuestas= collect($respuestas);

                
                try {
                    if ($id_formulario ==30) {
                        foreach ($respuestas as $key => $respuesta) {
                            $respuestas[$key]->cuanto_gana_al_mes_en_cordobas = (int) $respuesta->cuanto_gana_al_mes_en_cordobas;
                        }
                    }
                } catch (\Exception $ex) {
                }

                if ($table_format) {
                    $respuestas = new Paginator($respuestas, $rowsPerPage, $page+1, ["path"  => $request->url(), "query" => $request->query(),]);
                    $formulario = Formulario::with('tipo')->find($id_formulario);
                    $curso = FormulariosRespuesta::with('curso')->where('id_formulario', $id_formulario)->first();
                    return response()->json(["respuestas" => $respuestas, 'formulario' => $formulario, 'curso' => $curso], 200);
                }
                //Respuesta para webdataRocks: simple sin nombres
                return response()->json($respuestas, 200);
            } catch (\Throwable $th) {
                return response()->json(['result' => false, 'message' =>  'no hay respuestas para este formulario'.$th->getMessage()], 200);
            }
        }
        //Centros
        elseif ($id_formulario >= 10000 && $id_formulario < 20000) {
            $centros = CentrosDAL::getAllCentros();
            return response()->json($centros, 200);

        } elseif ($id_formulario >= 20000 && $id_formulario < 30000) {
           
            //Cursos
            $usuario = $request->user();
            $filtro = $request->has('filtro') ? $request->filtro : '';
           
            $dal = new CursosDAL($usuario, $filtro);
            $cursos = $dal->getAllCursos();
            return response()->json($cursos, 200);
        } elseif ($id_formulario >= 30000 && $id_formulario < 40000) {
            //Instructores
            $usuario = $request->user();
            $filtro = $request->filtro;

            $dal = new InstructoresDAL($usuario, $filtro);
            $instructores = $dal->getAllInstructores();
            return response()->json($instructores, 200);
        } elseif ($id_formulario >= 40000 && $id_formulario < 50000) {

            //Participantes
            $participantes = ParticipantesDAL::getAllParticipantes();
            return response()->json($participantes, 200);
        //Tests de Holland
        } elseif ($id_formulario >=500000) {
            $usuario = $request->user();
            $filtro = $request->has('filtro') ? $request->filtro : '';

            $page = $request->page == 0 ? 1 : $request->page;
            $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
            $sortBy = $request->sortBy ? $request->sortBy : 'id';
            $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';

            $dal = new HollandDAL($usuario, $request->filtro, $page, $rowsPerPage, $sortBy, $descending);
            $holland_tests =$dal->getAllTests();

            return response()->json($holland_tests, 200);
        }
    }


    public function DescargarReportesExcel(Request $request){

        $id_formulario = $request['id_formulario'];


        //Si es tipo formulario
        if ($id_formulario < 10000) {

            $usuario = $request->user();
            $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

            $page = 0;
            $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 99999999;
            $sortBy = $request->sortBy ? $request->sortBy : 'id';
            $descending = $request->has('descending') && $request->descending == 'true' ? 'desc' : 'asc';
            $filtro = $request->filtro;

            $id_formulario = $request['id_formulario'];
            $formulario = Formulario::find($id_formulario);
            $export = new FormularioRespuestasExport($id_formulario, $id_centros, $page, $rowsPerPage, $filtro);

            $log = new BitacoraHelper();
            $log->log($request, 'Descargar excel del formulario: '.$formulario->nombre, 'Formulario', $id_formulario);

            return $export->download('respuestas.xlsx');

        }
        //Centros
        elseif ($id_formulario >= 10000 && $id_formulario < 20000) {
            $export = new CentrosExport();
            $log = new BitacoraHelper();
            $log->log($request, 'Exporta centros a excel desde la pantalla de reportes', 'Centro', null);
            return $export->download('centros.xlsx');
        //Cursos
        } elseif ($id_formulario >= 20000 && $id_formulario < 30000) {
            $user = $request->user();
            $filtro = $request->filtro;
            $export = new CursosExport($user, $filtro);

            $log = new BitacoraHelper();
            $log->log($request, 'Descargar excel de los cursos desde la pantalla de reportes', 'Curso', null);
            return $export->download('cursos.xlsx');
           
        //Instructores
        } elseif ($id_formulario >= 30000 && $id_formulario < 40000) {
            $user = $request->user();
            $filtro = $request->filtro;
            $export = new InstructoresExport($user, $filtro);

            $log = new BitacoraHelper();
            $log->log($request, 'Exporta lista de instructores desde la pantalla de reportes', 'Instructores', null);
            return $export->download('instructores.xlsx');
          
        //Participantes
        } elseif ($id_formulario >= 40000 && $id_formulario < 50000) {

            $export = new ParticipantesExport();

            $log = new BitacoraHelper();
            $log->log($request, 'Exporta lista de participantes desde la pantalla de reportes', 'Participantes', null);
            return $export->download('participantes.xlsx');

    
        //Tests de Holland
        } elseif ($id_formulario >=500000) {
            $usuario = $request->user();
            $filtro = $request->has('filtro') ? $request->filtro : '';

            $page = $request->page == 0 ? 1 : $request->page;
            $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 999999999999999999;
            $sortBy = $request->sortBy ? $request->sortBy : 'id';
            $descending = $request->has('descending') && $request->descending == 'true' ? 'desc' : 'asc';
            $export = new TestsHollandExport($usuario, $filtro, $page, $rowsPerPage, $sortBy, $descending);

            $log = new BitacoraHelper();
            $log->log($request, 'Exporta tests de Holland desde la pantalla de reportes', 'Tests de Holland', null);

            return $export->download('test de holland.xlsx');
        }

       
    }

    public function DescargarRespuestas(Request $request)
    {
        $usuario = $request->user();
        $id_centros = implode(",", UsuariosCentro::where('id_usuario', $usuario->id)->pluck('id_centro')->unique()->toArray());

        $page = 0;
        $rowsPerPage = $request->rowsPerPage > 0 ? $request->rowsPerPage : 99999999;
        $sortBy = $request->sortBy ? $request->sortBy : 'id';
        $descending = $request->has('descending') && $request->descending =='true' ? 'desc' : 'asc';
        $filtro = $request->filtro;

        $id_formulario = $request['id_formulario'];
        $export = new FormularioRespuestasExport($id_formulario, $id_centros, $page, $rowsPerPage, $filtro);

        $log = new BitacoraHelper();
        $log->log($request, 'Exporta respuestas formulario', 'Formulario', $id_formulario);

        return $export->download('respuestas.xlsx');
    }

    

    public function getCamposEstadisticas(Request $request)
    {
        $id_formulario = $request['id_formulario'];
        $formulario = Formulario::find($request['id_formulario']);
        $id_formulario_respuesta = FormulariosRespuesta::where('id_formulario', $id_formulario)->pluck('id')->toArray();
        $id_formulario_respuesta_campos =  FormulariosRespuestasCampo::whereIn('id_formulario_respuesta', $id_formulario_respuesta)->pluck('id_formulario_campo')->unique();

        $global = DB::select(DB::raw("
		select id_campo, Pregunta,`Total respuestas`, Incorrectas,Correctas,Promotores,Pasivos,Detractores
		,round(`Nota promedio` ,2) as `Nota promedio`
		,round(`Valor Nota` ,2) as `Valor Nota`,
		round(correctas /  `Total respuestas` * 100,2) as `porcentaje correcto`,
        round(incorrectas /  `Total respuestas` * 100,2) as `porcentaje incorrecto` 
		from
				(
				select id_campo, pregunta as Pregunta, count(1) as  `Total respuestas`, 
                sum(case when postura = 'Detractor' then 1 else 0 end ) as Detractores,
sum(case when postura =  'Pasivo' then 1 else 0 end) as Pasivos,sum(case when postura = 'Promotor' then 1 else 0 end ) as Promotores ,
				sum(case when respuesta ='Incorrecto' then 1 else 0 end) as Incorrectas, 
				sum(case when respuesta ='Correcto' then 1 else 0 end) as Correctas,
				avg(nota) as `Nota promedio`,
				avg(valor_nota) as `Valor Nota`
				from 
				(
				select fc.id as id_campo, fc.texto as pregunta, 
                case when fc.nota <> frc.nota then 'Incorrecto' else 'Correcto' end as respuesta, frc.nota, fc.nota as valor_nota,
                case when frc.valor <=6 then 'Detractor' 
                    when frc.valor >6 and frc.valor <9 then 'Pasivo' 
                    else  'Promotor' end as postura
				from  formularios_respuestas fr
				inner join formularios_respuestas_campos frc on fr.id = frc.id_formulario_respuesta
				inner join formularios as f on fr.id_formulario = f.id
				inner join formularios_campos fc on frc.id_formulario_campo = fc.id
				where fr.id_formulario =$id_formulario
				)a
				group by id_campo, pregunta
			)b;
            
		"));

        $estadisticas = [];
        foreach ($id_formulario_respuesta_campos as $key => $campo) {
            $_campo = FormulariosCampo::find($campo);
            if ($_campo->id_tipo ==6) {
                $estadistica = 	DB::select(DB::raw("select  valor as x,cast(porcentaje as unsigned) as y from (
					select *, cantidad /total*100 as porcentaje from (
						select texto,valor,total,count(1) as cantidad from(
                        select fc.texto,
                    case when frc.valor <=6 then 'Detractores' 
                    when frc.valor >6 and frc.valor <9 then 'Pasivos' 
                    else  'Promotores' end as valor,( select count(1) from formularios_respuestas_campos  frc
					where id_formulario_Respuesta in (select id from formularios_respuestas where id_formulario = $id_formulario)
					and id_formulario_campo =$campo ) as  total
					from formularios_respuestas_campos  frc
					inner join formularios_campos fc on frc.id_formulario_campo = fc.id
					where id_formulario_Respuesta in (select id from formularios_respuestas where id_formulario = $id_formulario)
					and id_formulario_campo =$campo
                        )x
						group by texto,valor,total
						)A
					)B;

                    "));
                $form_campo = FormulariosCampo::find($campo);
            } else {
                $estadistica = 	DB::select(DB::raw("select  valor as x,cast(porcentaje as unsigned) as y from (
						select *, cantidad /total*100 as porcentaje from (
						select fc.texto, frc.valor ,( select count(1) from formularios_respuestas_campos  frc
						where id_formulario_Respuesta in (select id from formularios_respuestas where id_formulario = $id_formulario)
						and id_formulario_campo =$campo ) as  total, count(1) as cantidad
						from formularios_respuestas_campos  frc
						inner join formularios_campos fc on frc.id_formulario_campo = fc.id
						where id_formulario_Respuesta in (select id from formularios_respuestas where id_formulario = $id_formulario)
						and id_formulario_campo =$campo
						group by fc.texto,frc.valor
							)A
						)B"));
                $form_campo = FormulariosCampo::find($campo);
            }
            array_push($estadisticas, ['name' => $form_campo->texto, 'id_tipo_formulario' => $formulario->id_tipo, 'id' => $form_campo->id, 'tipo' => $form_campo->id_tipo, 'tipo_input' =>$form_campo->tipo_input, 'estadistica' => $estadistica]);
        }

        $respuestas = FormulariosRespuestasCampo::whereIn('id_formulario_respuesta', $id_formulario_respuesta)->get();
        return response()->json(['estadisticas' => $estadisticas,  'global' => $global, 'respuestas' => $respuestas]);
    }


    public function CambiarResultadoRespuesta(Request $request)
    {
        $id_campo = $request['id_campo'];
        $correcta  = $request['correcta'];
        $id_formulario_respuesta = $request['id_formulario_respuesta'];

        $campo_respuesta = FormulariosRespuestasCampo::find($id_campo);
        $campo = FormulariosCampo::find($campo_respuesta->id_formulario_campo);


        try {
            DB::beginTransaction();
            $campo_respuesta->nota = $correcta ? $campo->nota : 0;
            $campo_respuesta->evaluada  = true;
            $campo_respuesta->save();

            $formulario_campos = FormulariosRespuestasCampo::where([
                ['id_formulario_respuesta', $id_formulario_respuesta],
            ])->get();

            $nota_total = 0;
            foreach ($formulario_campos as $key => $campo) {
                $nota_total += (int) $campo->nota;
            }
            $formulario_respuesta = FormulariosRespuesta::find($id_formulario_respuesta);
            $formulario_respuesta->nota =  $nota_total;
            $formulario_respuesta->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
        }

        return response()->json(['result' => true], 200);
    }

    public function ListadoUsuariosCentros()
    {
        $usuarios = Centro::with(["usuarios" => function ($q) {
            $q->select('usuarios.id as id_usuario', 'usuarios.nombre as label');
        }])->select("id", "nombre as label")->get();

        return response()->json(['usuarios' => $usuarios]);
    }


    public function compartirFormulario(Request $request)
    {
        $id_curso = $request['id_curso'];
        $id_formulario = $request['id_formulario'];
        $filtrarCorreo= '';
        $correo = $request['correo'];

        $usuarios = CursosMatricula::
        join('cursos', 'cursos_matriculas.id_curso', 'cursos.id')
        ->join('catalogo_cursos', 'cursos.id_curso', 'catalogo_cursos.id')
        ->join('centros', 'catalogo_cursos.id_centro', 'centros.id')
        ->where([
            ['cursos_matriculas.id_curso',$id_curso],
            ['cursos_matriculas.correo','<>',null],
            ['cursos.id_estado' ,'>=',5532]
        ]);

        if ($request->has('correo') && isset($correo)) {
            $filtrarCorreo = "cursos_matriculas.correo = '$correo'";
            $usuarios->whereRaw($filtrarCorreo, []);
        }

      
        $usuarios->selectRaw("concat(cursos_matriculas.nombres_participante, ' ' ,
		cursos_matriculas.apellidos_participante) as nombre_participante,
		cursos_matriculas.telefono,cursos_matriculas.correo,
		centros.nombre as centro,catalogo_cursos.nombre as nombre_curso,cursos_matriculas.id_curso,
		catalogo_cursos.nombre as nombre_curso,catalogo_cursos.id_centro", [ ])
        ->get();

        //Si no encontramos participantes regresamos un error
        if ($usuarios->count()===0) {
            return response()->json([
                'result'=> false,
                'message'=> 'El curso seleccionado no posee participantes vinculados al mismo'], 404);
        }

        $formulario = Formulario::find($id_formulario);
        Emails::EnviarEmailEnlaceFormulario($usuarios, $formulario);

        return response()->json([
            'result'=>true,
            'message'=> 'Se ha enviado un correo a todos los participantes del curso compartiendo el enlace del formulario'
            ]);
    }
}
