<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Super Usuario',
                'nivel' => 1,
                'created_at' => '2019-07-23 20:25:34',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Administrador',
                'nivel' => 2,
                'created_at' => '2019-07-23 20:25:34',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Asesor',
                'nivel' => 3,
                'created_at' => '2019-07-23 20:25:34',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nombre' => 'Centro',
                'nivel' => 4,
                'created_at' => '2019-07-23 20:25:34',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'nombre' => 'Instructor',
                'nivel' => 5,
                'created_at' => '2019-09-22 02:54:31',
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'nombre' => 'Invitado',
                'nivel' => 6,
                'created_at' => '2019-09-23 08:52:27',
                'updated_at' => '2019-09-23 08:52:27',
            ),
        ));
        
        
    }
}