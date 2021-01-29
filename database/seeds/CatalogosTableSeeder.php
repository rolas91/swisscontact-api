<?php

use Illuminate\Database\Seeder;

class CatalogosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('catalogos')->delete();
        
        \DB::table('catalogos')->insert(array (
            0 => 
            array (
                'id' => 29,
                'codigo' => '29',
                'nombre' => 'Paises',
                'activo' => 1,
                'created_at' => '2019-07-24 23:37:54',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 30,
                'codigo' => '30',
                'nombre' => 'Departamentos',
                'activo' => 1,
                'created_at' => '2019-07-24 23:48:37',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 31,
                'codigo' => '31',
                'nombre' => 'Municipios',
                'activo' => 1,
                'created_at' => '2019-07-24 23:54:55',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 32,
                'codigo' => '32',
                'nombre' => 'Niveles Academicos',
                'activo' => 1,
                'created_at' => '2019-07-29 02:17:42',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 33,
                'codigo' => '33',
                'nombre' => 'Tipo identificacion',
                'activo' => 1,
                'created_at' => '2019-08-22 02:48:15',
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 34,
                'codigo' => '34',
                'nombre' => 'Sectores',
                'activo' => 1,
                'created_at' => '2019-08-28 22:34:39',
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 35,
                'codigo' => '35',
                'nombre' => 'Subcategorias',
                'activo' => 1,
                'created_at' => '2019-08-28 22:34:39',
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 36,
                'codigo' => '36',
                'nombre' => 'Unidad Duracion',
                'activo' => 1,
                'created_at' => '2019-08-28 22:34:39',
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 37,
                'codigo' => '37',
                'nombre' => 'Nivel Dificultad Curso',
                'activo' => 1,
                'created_at' => '2019-08-28 22:34:39',
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 38,
                'codigo' => '38',
                'nombre' => 'Estado Curso',
                'activo' => 1,
                'created_at' => '2019-08-28 22:34:39',
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 39,
                'codigo' => '39',
                'nombre' => 'Tipo Curso',
                'activo' => 1,
                'created_at' => '2019-08-28 22:34:39',
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 40,
                'codigo' => '40',
                'nombre' => 'Estados Civiles',
                'activo' => 1,
                'created_at' => '2019-09-08 22:55:26',
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 41,
                'codigo' => '41',
                'nombre' => 'Modalidades',
                'activo' => 1,
                'created_at' => '2019-09-10 03:54:40',
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 42,
                'codigo' => '42',
                'nombre' => 'Modos',
                'activo' => 1,
                'created_at' => '2019-09-10 03:55:05',
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 43,
                'codigo' => '43',
                'nombre' => 'Parentesco',
                'activo' => 1,
                'created_at' => '2019-09-10 05:16:17',
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 44,
                'codigo' => '44',
                'nombre' => 'Tipo Formulario',
                'activo' => 1,
                'created_at' => '2019-09-23 00:30:04',
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 45,
                'codigo' => '45',
                'nombre' => 'Tipos de campos formulario',
                'activo' => 1,
                'created_at' => '2019-09-25 11:04:58',
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 46,
                'codigo' => '46',
                'nombre' => 'Temas Formularios',
                'activo' => 1,
                'created_at' => '2019-09-25 11:36:54',
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 47,
                'codigo' => '47',
                'nombre' => 'Tipo Centros',
                'activo' => 1,
                'created_at' => '2019-11-07 22:12:42',
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 48,
                'codigo' => '48',
                'nombre' => 'Modos Fomularios',
                'activo' => 1,
                'created_at' => '2019-11-18 23:26:55',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}