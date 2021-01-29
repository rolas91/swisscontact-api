<?php

use Illuminate\Database\Seeder;

class AccesosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accesos')->delete();
        
        \DB::table('accesos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Dashboard',
                'descripcion' => 'Dashboard gerencial',
                'icon' => 'fas fa-tachometer-alt',
                'path' => '/admin/dashboard',
                'orden' => 1,
                'created_at' => '2019-09-22 10:30:31',
                'updated_at' => '2019-09-22 10:30:31',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Reportes',
                'descripcion' => 'Reportes Gerenciales',
                'icon' => 'fas fa-chart-pie',
                'path' => '/admin/reportes',
                'orden' => 2,
                'created_at' => '2019-09-22 10:31:02',
                'updated_at' => '2019-09-22 10:31:02',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Centros',
                'descripcion' => 'Centros de educación auspiciados por Swisscontact',
                'icon' => 'fas fa-school',
                'path' => '/admin/centros',
                'orden' => 3,
                'created_at' => '2019-09-22 10:32:09',
                'updated_at' => '2019-09-22 10:32:09',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Instructores',
                'descripcion' => 'Instructores o Profesores que imparten los cursos en los diferentes centros',
                'icon' => 'fas fa-chalkboard-teacher',
                'path' => '/admin/instructores',
                'orden' => 4,
                'created_at' => '2019-09-22 10:33:17',
                'updated_at' => '2019-09-22 10:33:17',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'nombre' => 'Listado de Cursos',
                'descripcion' => 'Cursos, Carreras, Seminarios Impartidos en los diferentes centros de educación',
                'icon' => 'fas fa-book',
                'path' => '/catalogos/cursos',
                'orden' => 5,
                'created_at' => '2019-09-22 10:34:01',
                'updated_at' => '2019-11-23 03:20:13',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'nombre' => 'Participantes',
                'descripcion' => 'Participantes o estudiantes que son los beneficiados en los diferentes cursos',
                'icon' => 'fas fa-user-graduate',
                'path' => '/admin/participantes',
                'orden' => 6,
                'created_at' => '2019-09-22 10:35:15',
                'updated_at' => '2019-09-22 10:35:15',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'nombre' => 'Formularios',
                'descripcion' => 'Formularios Dinamicos para realizar encuentas o examens',
                'icon' => 'far fa-edit',
                'path' => '/admin/formularios',
                'orden' => 7,
                'created_at' => '2019-09-22 10:35:43',
                'updated_at' => '2019-09-22 10:35:43',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'nombre' => 'Usuarios',
                'descripcion' => 'Listado de usuarios del sistema',
                'icon' => 'fas fa-users',
                'path' => '/admin/usuarios',
                'orden' => 8,
                'created_at' => '2019-09-22 10:36:15',
                'updated_at' => '2019-09-22 10:36:15',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'nombre' => 'Roles',
                'descripcion' => 'Administra los roles o perfiles para los diferentes tipos de usuarios',
                'icon' => 'fas fa-user-tag',
                'path' => '/admin/roles',
                'orden' => 9,
                'created_at' => '2019-09-22 10:38:18',
                'updated_at' => '2019-09-22 10:41:57',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'nombre' => 'Accesos',
                'descripcion' => 'Administra los accesos y rutas del sistema',
                'icon' => 'fas fa-unlock-alt',
                'path' => '/admin/accesos',
                'orden' => 10,
                'created_at' => '2019-09-22 10:41:37',
                'updated_at' => '2019-09-22 10:41:37',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'nombre' => 'Permisos',
                'descripcion' => 'Administra los accesos y permisos para los diferentes roles',
                'icon' => 'fas fa-user-lock',
                'path' => '/admin/rolesAccesos',
                'orden' => 11,
                'created_at' => '2019-09-22 10:43:11',
                'updated_at' => '2019-09-22 10:43:11',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'nombre' => 'Bitacora',
                'descripcion' => 'Bitacora',
                'icon' => 'fas fa-clipboard-check',
                'path' => '/admin/bitacora',
                'orden' => 12,
                'created_at' => '2019-10-08 08:05:01',
                'updated_at' => '2019-10-08 08:05:01',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'nombre' => 'Inscripciones',
                'descripcion' => 'Inscripciones',
                'icon' => 'fas fa-graduation-cap',
                'path' => '/admin/inscripciones',
                'orden' => 6,
                'created_at' => '2019-10-30 04:36:59',
                'updated_at' => '2020-02-11 14:06:24',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 17,
                'nombre' => 'Ayuda',
                'descripcion' => 'Ayuda',
                'icon' => 'fas fa-question-circle',
                'path' => '/ayuda',
                'orden' => 15,
                'created_at' => '2019-11-12 21:41:04',
                'updated_at' => '2019-11-12 21:41:04',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 18,
                'nombre' => 'Administración de cursos',
                'descripcion' => 'Administración de cursos',
                'icon' => 'fas fa-toolbox',
                'path' => '/admin/cursos',
                'orden' => 5,
                'created_at' => '2019-11-23 03:18:44',
                'updated_at' => '2019-11-23 03:19:07',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 19,
                'nombre' => 'Test de Holland',
                'descripcion' => 'Test de Holland',
                'icon' => 'fas fa-drafting-compass',
                'path' => '/formularios/test-holland',
                'orden' => 18,
                'created_at' => '2020-01-27 13:57:37',
                'updated_at' => '2020-01-27 13:57:37',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}