<?php

use Illuminate\Database\Seeder;

class FormulariosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('formularios')->delete();
        
        \DB::table('formularios')->insert(array (
            0 => 
            array (
                'id' => 27,
                'id_tipo' => 5560,
                'id_tema' => 5569,
                'nombre' => 'Linea Base 2020',
                'url' => 'zPSLb9Da',
                'fecha_inicio' => '2019-10-07',
                'fecha_fin' => '2019-10-07',
                'duracion' => '0.00',
                'nota_maxima' => '0.00',
                'ordenar_aleatoriamente' => 0,
                'id_modo' => 5603,
                'id_usuario_creacion' => 1,
                'id_usuario_modificacion' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-02-04 10:58:50',
                'updated_at' => '2020-02-13 03:21:53',
            ),
            1 => 
            array (
                'id' => 29,
                'id_tipo' => 5560,
                'id_tema' => 5569,
                'nombre' => 'SEGUIMIENTO A CURSOS - ESTUDIANTE',
                'url' => 'jErGMCVq',
                'fecha_inicio' => '2020-02-13',
                'fecha_fin' => '2020-02-13',
                'duracion' => '0.00',
                'nota_maxima' => '0.00',
                'ordenar_aleatoriamente' => 1,
                'id_modo' => 5603,
                'id_usuario_creacion' => 1,
                'id_usuario_modificacion' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-02-13 03:42:17',
                'updated_at' => '2020-02-13 03:53:16',
            ),
            2 => 
            array (
                'id' => 30,
                'id_tipo' => 5560,
                'id_tema' => 5569,
                'nombre' => 'Linea_base_datos_2018_2019',
                'url' => 'oFUWzMU3',
                'fecha_inicio' => '2018-01-01',
                'fecha_fin' => '2019-12-31',
                'duracion' => '0.00',
                'nota_maxima' => '0.00',
                'ordenar_aleatoriamente' => 0,
                'id_modo' => 5603,
                'id_usuario_creacion' => 1,
                'id_usuario_modificacion' => 1,
                'deleted_at' => NULL,
                'created_at' => '2020-02-18 08:15:12',
                'updated_at' => '2020-03-06 11:49:36',
            ),
        ));
        
        
    }
}