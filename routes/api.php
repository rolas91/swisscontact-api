<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/test', function (Request $request) {
    return "esta es una prueba";
});

Route::post('/correo_prueba', "SystemController@TestEmail");



Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', function (request $request) {
        return $request->user();
    });
    
    Route::post('/logout', 'AuthController@logout');

    Route::get('/catalogos/paises', 'CatalogosController@Paises')->name('catalogo-paises');
  
    Route::get('/catalogos/TodosMunicipios', 'CatalogosController@TodosMunicipios')->name('todos-municipios');
    Route::get('/catalogos/nivel_academicos', 'CatalogosController@nivelesAcademicos')->name('nivel_academicos');
    Route::get('/catalogos/tipo_identificaciones', 'CatalogosController@TipoIdentificaciones')->name('catalogo-tipo_identificaciones');
    Route::get('/catalogos/roles', 'CatalogosController@Roles')->name('catalogo-roles');
    Route::get('/admin/usuarios/disponibles_instructor', 'UsuariosController@UsuariosDisponiblesinstructor');
    Route::get('/admin/usuarios/forEdit', 'UsuariosController@forEdit');
    

    //Centros
    


    Route::middleware('permisos:ver,8')->resource('/admin/usuarios', 'UsuariosController');
    Route::middleware('permisos:ver,3')->resource('admin/centros', 'CentrosController');
    Route::middleware('permisos:ver,3')->post('/admin/centros/reactivar/{id}', 'CentrosController@reactivarCentro');
    Route::middleware('permisos:ver,4')->resource('/admin/instructores', 'InstructoresController');
   
    Route::post('/admin/centros/uploadImage', 'CentrosController@uploadimage');
    Route::get('/catalogos/tipoCentros', 'CatalogosController@tipoCentros');

    //Cursos
    Route::get('/catalogos/tipoCursos', 'CatalogosController@TipoCursos')->name('tipo_cursos');
    Route::get('/catalogos/categorias', 'CatalogosController@Categorias')->name('categorias');
    Route::get('/catalogos/sectores', 'CatalogosController@sectores')->name('sectores');
    Route::get('/catalogos/subcategorias', 'CatalogosController@Subcategorias')->name('subcategorias');
    Route::get('/catalogos/nivel_dificultades', 'CatalogosController@NivelDificultades')->name('nivel_dificultades');
    Route::get('/catalogos/modalidades', 'CatalogosController@Modalidades')->name('modalidades');
    Route::get('/catalogos/modos', 'CatalogosController@Modos')->name('modos');
    Route::get('/catalogos/centros', 'CatalogosController@Centros')->name('centros');
    Route::get('/catalogos/centros_cursos', 'CatalogosController@CentrosCursos')->name('centros');
    Route::get('/catalogos/unidad_duraciones', 'CatalogosController@UnidadDuraciones')->name('unidad_duraciones');
    Route::get('/catalogos/estados_curso', 'CatalogosController@EstadosCurso')->name('estados_curso');
    Route::middleware('permisos:ver,18')->resource('/admin/cursos', 'CursosController');
    Route::middleware('permisos:editar,18')->post('/cursos/cambiarEstado', 'CursosController@cambiarEstado');

    //Catalogo Cursos
    Route::middleware('permisos:ver,5')->get('/catalogos/cursos/index', 'CursosController@indexCatalogoCurso');
    Route::middleware('permisos:crear,5')->post('/catalogos/cursos', 'CursosController@storeCatalogoCurso');
    Route::middleware('permisos:editar,5')->put('/catalogos/cursos/{id_curso}', 'CursosController@updateCatalogoCurso');
    Route::middleware('permisos:editar,5')->get('/catalogos/cursos/{id_curso}/edit', 'CursosController@editCatalogoCurso');
    Route::middleware('permisos:eliminar,5')->delete('/catalogos/cursos/{id_curso}', 'CursosController@destroyCatalogoCurso');

    Route::get('/catalogos/catalogo_cursos', 'CatalogosController@CatalogoCursos');
    Route::get('/curso/obtener_codigo', 'CursosController@getCodigo');

    Route::get('inscripciones/getDatosCurso','CursosController@getDatos');

  

    //Table: cursos_matriculas
    
    Route::get('/catalogos/instructores', 'CatalogosController@instructores')->name('instructores');
    Route::get('/catalogos/tipo_identificaciones', 'CatalogosController@TipoIdentificaciones')->name('tipo_identificaciones');
    Route::get('/catalogos/estado_civiles', 'CatalogosController@EstadoCiviles')->name('estado_civiles');
    Route::get('/catalogos/participantes', 'CatalogosController@Participantes');
    Route::get('/catalogos/parentescos', 'CatalogosController@Parentescos');
    Route::middleware('permisos:ver,13')->resource('/admin/cursos_matriculas', 'CursosMatriculasController');
     
    //Formularios
    Route::get('/catalogos/tipos_formularios', 'CatalogosController@tipoFormulario');
    Route::get('/catalogos/tipos_campos_formularios', 'CatalogosController@tipoCampoFormulario');
    Route::get('/catalogos/temas_formularios', 'CatalogosController@TemasFormularios');
    Route::middleware('permisos:ver,7')->resource('/admin/formularios', 'FormulariosController');
    Route::middleware('permisos:editar,7')->post('/formulario/cambiar_resultado_respuesta', 'FormulariosController@CambiarResultadoRespuesta');
    Route::middleware('permisos:crear,7')->post('/formularios/crear', 'FormulariosController@store');
    Route::middleware('permisos:editar,7')->post('/formularios/editar/{id_formulario}', 'FormulariosController@update');


    Route::post('/formulario/estadisticas/respuestas/', 'FormulariosController@getCamposEstadisticas');
    Route::post('/verificar_correo/formulario', 'FormulariosController@verificarCorreo');
    Route::post('/respuestas/formulario/descargar', 'FormulariosController@DescargarRespuestas');
    Route::post('/admin/formularios/uploadImage', 'FormulariosController@uploadimage');

    //Table: participantes

    Route::middleware('permisos:ver,6')->resource('/admin/participantes', 'ParticipantesController');
    Route::post('/admin/participantes/uploadImage', 'ParticipantesController@uploadimage');

    //  Route::resource('/admin/formularios', 'FormulariosController');
     
     
    //Table: roles_accesos
    Route::get('/catalogos/accesos', 'CatalogosController@Accesos')->name('accesos');
     
    //Table: roles_accesos
    Route::middleware('permisos:ver,11')->resource('/admin/roles_accesos', 'RolesAccesosController');
    Route::middleware('permisos:ver,10')->resource('/admin/accesos', 'AccesosController');
    
    //Table roles
    Route::middleware('permisos:ver,9')->resource('/admin/roles', 'RolesController');
    Route::get('/catalogos/rolesAccesos/edit', 'CatalogosController@AccesosEdit');
    
    
    //Reportes
    Route::middleware('permisos:ver,2')->resource('admin/reportes', 'ReportesController');
    Route::get('/reportes/SetDatos', 'ReportesController@SetDatos');
    
    //Excel
    Route::post('/centros/descargar', 'CentrosController@DescargarExcel');
    Route::post('/participantes/descargar', 'ParticipantesController@DescargarExcel');
    Route::post('/instructores/descargar', 'InstructoresController@DescargarExcel');
    Route::post('/cursos/descargar', 'CursosController@DescargarExcel');
    Route::post('/bitacora/descargar', 'BitacoraController@DescargarExcel');
    Route::post('/usuarios/descargar', 'UsuariosController@DescargarExcel');
    Route::post('/inscripciones/descargar', 'CursosMatriculasController@DescargarExcel');
    Route::post('/reportes/descargar', 'FormulariosController@DescargarReportesExcel');
    
    //Bitacora
    Route::middleware('permisos:ver,12')->resource('/admin/bitacora', 'BitacoraController');
    
    
    Route::get('/respuestas/formulario/{id_formulario}', 'FormulariosController@RespuestasFormulario');
    Route::get('/usuarios/accesos', 'UsuariosController@getAccesos');


    Route::post('/formularios/compartir_enlace', 'FormulariosController@compartirFormulario');
    Route::get('/admin/listado_usuarios_centros', 'FormulariosController@ListadoUsuariosCentros');



    Route::get('admin/catalogos/cursos', 'CatalogosController@UsuarioCursos')->name('usuario-cursos') ;
    Route::post('/admin/cursos_matriculas/{id_matricula}/toggle-egresado', 'CursosMatriculasController@toggleEgresado');


});

Route::post('/formularios/test-holland/responder', 'HollandController@store');

Route::get('/fornularios/test-holland/test-activo/{token}', 'HollandController@TestHollandActivo');

Route::get('/catalogos/cursos', 'CatalogosController@Cursos')->name('cursos') ;


Route::middleware(['auth:api'])->group(function () {
    Route::get('/formularios/test-holland/index', 'HollandController@IndexHollandTest');
    Route::post('/formularios/test-holland/store', 'HollandController@StoreHollandTest');
    Route::get('/imprimir-formulario/{id_formulario}', 'FormulariosController@imprimirFormulario');
    Route::get('/formularios/test-holland/{id}/edit', 'HollandController@editHollandTest');
    Route::put('/formularios/test-holland/update/{id}', 'HollandController@UpdateHollandTest');
    Route::delete('formularios/test-holland/destroy/{id}', 'HollandController@destroyHollandTest');
    Route::get('/formularios/test-holland/{token}/respuestas', 'HollandController@GetRespuestas');
    Route::get('/formularios/test-holland/{token}/resultado/{idResultado}', 'HollandController@getResultado');
    Route::post('/formularios/holland_respuestas/descargar', 'HollandController@DescargarRespuestasTestHollandExcel');
    Route::post('/formularios/holland_tests/descargar', 'HollandController@DescargarTestsHollandExcel');
    Route::get('/catalogos/holland-tests-centros', 'HollandController@HollandCentros');
    Route::get('/centros/instructores', 'CentrosController@CargarInstructores');
    Route::get('/test-holland/getTests','HollandController@GetHollandTests');
});

//reportes publicos
Route::get('/catalogos/cursos/public', 'CatalogosController@Cursos');

Route::get('/reportes/DistribucionCentros', 'ReportesController@DistribucionCentros');
Route::get('/reportes/CentrosXDepartamento', 'ReportesController@CentrosXDepartamento');
Route::get('/reportes/DistribucionCentrosXDepartamento', 'ReportesController@DistribucionCentrosXDepartamento');

Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/usuario/verificar_correo', 'UsuariosController@VerificarCorreo')->name('verificar_correo');
    Route::post('/usuario/cambiar_contrasenia', 'UsuariosController@CambiarContrasenia')->name('cambiar_contrasenia');


      Route::post('/responder/formulario/{slug}', 'FormulariosController@responderFormulario');
  
    Route::get('/formularios/{id_formulario}/respuesta/{id_respuesta}', 'FormulariosController@getFormularioRespuesta');



    Route::get('/responder/formulario/{slug}', 'FormulariosController@getFormulario');
    
    Route::get('/formularios/{id_formulario}/respuesta/{id_respuesta}/evaluar', 'FormulariosController@getFormularioRespuestaEvaluar');
   
     Route::post('/verificar_identidad/formulario', 'FormulariosController@verificarIdentidad');
     Route::get('/reportes/test', 'ReportesController@test');
     Route::get('/catalogos/centros_cursos', 'CatalogosController@CentrosCursos')->name('centros');
     Route::post('/usuario/resetear-contrasenia', 'UsuariosController@ResetearContrasenia');

     Route::get('/formularios/test-holland', 'HollandController@index');
     Route::post('/test-holland/buscar-participante','HollandController@BuscarTestHollandParticipante');

    Route::get('/catalogos/departamentos', 'CatalogosController@departamentos')->name('catalogo-departamentos');
    Route::get('/catalogos/municipios', 'CatalogosController@municipios')->name('catalogo-municipios');
