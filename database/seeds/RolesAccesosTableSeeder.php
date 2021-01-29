<?php

use Illuminate\Database\Seeder;

class RolesAccesosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles_accesos')->delete();
        
        \DB::table('roles_accesos')->insert(array (
            0 => 
            array (
                'id' => 34,
                'id_acceso' => 10,
                'id_rol' => 3,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 35,
                'id_acceso' => 3,
                'id_rol' => 3,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 36,
                'id_acceso' => 5,
                'id_rol' => 3,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 37,
                'id_acceso' => 1,
                'id_rol' => 3,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 38,
                'id_acceso' => 7,
                'id_rol' => 3,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 39,
                'id_acceso' => 4,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 40,
                'id_acceso' => 6,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 41,
                'id_acceso' => 11,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 42,
                'id_acceso' => 2,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 43,
                'id_acceso' => 9,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 44,
                'id_acceso' => 8,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 89,
                'id_acceso' => 10,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 90,
                'id_acceso' => 3,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 91,
                'id_acceso' => 5,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 92,
                'id_acceso' => 1,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 93,
                'id_acceso' => 7,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 94,
                'id_acceso' => 4,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 95,
                'id_acceso' => 6,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 96,
                'id_acceso' => 11,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 97,
                'id_acceso' => 2,
                'id_rol' => 6,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 98,
                'id_acceso' => 9,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 99,
                'id_acceso' => 8,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-09-08 12:00:00',
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 124,
                'id_acceso' => 10,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 125,
                'id_acceso' => 3,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 126,
                'id_acceso' => 5,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 127,
                'id_acceso' => 1,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 128,
                'id_acceso' => 7,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 129,
                'id_acceso' => 4,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 130,
                'id_acceso' => 6,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 131,
                'id_acceso' => 11,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 132,
                'id_acceso' => 2,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 133,
                'id_acceso' => 9,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 134,
                'id_acceso' => 8,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 135,
                'id_acceso' => 12,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 136,
                'id_acceso' => 13,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-10-09 12:00:00',
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 138,
                'id_acceso' => 17,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-11-12 21:41:04',
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 140,
                'id_acceso' => 17,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-11-12 21:41:04',
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 143,
                'id_acceso' => 17,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-11-12 21:41:04',
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 189,
                'id_acceso' => 18,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2019-11-23 03:18:44',
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 191,
                'id_acceso' => 18,
                'id_rol' => 3,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-11-23 03:18:44',
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 194,
                'id_acceso' => 18,
                'id_rol' => 6,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2019-11-23 03:18:44',
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 208,
                'id_acceso' => 19,
                'id_rol' => 1,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-01-27 13:57:37',
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 210,
                'id_acceso' => 19,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-01-27 13:57:37',
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 213,
                'id_acceso' => 19,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-01-27 13:57:37',
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 228,
                'id_acceso' => 17,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 229,
                'id_acceso' => 1,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 230,
                'id_acceso' => 2,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 231,
                'id_acceso' => 3,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 232,
                'id_acceso' => 4,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 233,
                'id_acceso' => 5,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 234,
                'id_acceso' => 6,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 235,
                'id_acceso' => 7,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 236,
                'id_acceso' => 8,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 237,
                'id_acceso' => 9,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 238,
                'id_acceso' => 10,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 239,
                'id_acceso' => 11,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 240,
                'id_acceso' => 12,
                'id_rol' => 5,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 241,
                'id_acceso' => 13,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 242,
                'id_acceso' => 18,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 243,
                'id_acceso' => 19,
                'id_rol' => 5,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 245,
                'id_acceso' => 12,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-04 17:42:30',
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 247,
                'id_acceso' => 12,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-04 17:42:30',
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 249,
                'id_acceso' => 13,
                'id_rol' => 3,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-04 17:42:30',
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 251,
                'id_acceso' => 13,
                'id_rol' => 6,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-04 17:42:30',
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 291,
                'id_acceso' => 10,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 292,
                'id_acceso' => 3,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 293,
                'id_acceso' => 5,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 294,
                'id_acceso' => 1,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 295,
                'id_acceso' => 7,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 296,
                'id_acceso' => 4,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 297,
                'id_acceso' => 6,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 298,
                'id_acceso' => 11,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 299,
                'id_acceso' => 2,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 300,
                'id_acceso' => 9,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 301,
                'id_acceso' => 8,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 302,
                'id_acceso' => 17,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 303,
                'id_acceso' => 18,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 304,
                'id_acceso' => 19,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 305,
                'id_acceso' => 12,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 306,
                'id_acceso' => 13,
                'id_rol' => 2,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-01 18:00:00',
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 307,
                'id_acceso' => 10,
                'id_rol' => 4,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 308,
                'id_acceso' => 3,
                'id_rol' => 4,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 309,
                'id_acceso' => 5,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 310,
                'id_acceso' => 1,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 311,
                'id_acceso' => 7,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 312,
                'id_acceso' => 4,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 313,
                'id_acceso' => 6,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 314,
                'id_acceso' => 11,
                'id_rol' => 4,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 315,
                'id_acceso' => 2,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 316,
                'id_acceso' => 9,
                'id_rol' => 4,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 317,
                'id_acceso' => 8,
                'id_rol' => 4,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 318,
                'id_acceso' => 17,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 319,
                'id_acceso' => 18,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 320,
                'id_acceso' => 19,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 1,
                'editar' => 1,
                'eliminar' => 1,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 321,
                'id_acceso' => 12,
                'id_rol' => 4,
                'ver' => 0,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 322,
                'id_acceso' => 13,
                'id_rol' => 4,
                'ver' => 1,
                'crear' => 0,
                'editar' => 0,
                'eliminar' => 0,
                'created_at' => '2020-02-02 00:00:00',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}