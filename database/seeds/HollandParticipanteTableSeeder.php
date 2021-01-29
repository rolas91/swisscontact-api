<?php

use Illuminate\Database\Seeder;

class HollandParticipanteTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('holland_participante')->delete();
        
        \DB::table('holland_participante')->insert(array (
            0 => 
            array (
                'id' => 1,
                'creado' => '2020-02-05 09:09:21',
                'actualizado' => NULL,
                'test_id' => 1,
                'id_participante' => NULL,
                'correo' => 'ireyes@innovactioncorp.com',
                'token' => NULL,
                'invitacion_enviada' => '2020-02-05 09:09:21',
                'personalidad' => 'emprendedor',
                'telefono' => '88963214',
                'cedula' => '001-010299-0025Q',
                'nombres' => 'Israel',
                'apellidos' => 'Reyes',
            ),
            1 => 
            array (
                'id' => 2,
                'creado' => '2020-02-05 11:35:33',
                'actualizado' => NULL,
                'test_id' => 2,
                'id_participante' => NULL,
                'correo' => '4e71795@xmailsme.com',
                'token' => NULL,
                'invitacion_enviada' => '2020-02-05 11:35:33',
                'personalidad' => 'investigador',
                'telefono' => '85456521',
                'cedula' => '001-151290-0008K',
                'nombres' => 'Juan',
                'apellidos' => 'García',
            ),
        ));
        
        
    }
}