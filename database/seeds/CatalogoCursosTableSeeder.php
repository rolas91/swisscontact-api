<?php

use Illuminate\Database\Seeder;

class CatalogoCursosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('catalogo_cursos')->delete();
        
        \DB::table('catalogo_cursos')->insert(array (
            0 => 
            array (
                'id' => 12237,
                'id_tipo' => 5535,
                'id_centro' => 23,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Cajero       ',
                'descripcion' => 'Cajero       ',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 12238,
                'id_tipo' => 5535,
                'id_centro' => 23,
                'id_sector' => 5608,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Cocina básica',
                'descripcion' => 'Cocina básica',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 12239,
                'id_tipo' => 5535,
                'id_centro' => 23,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección',
                'descripcion' => 'Corte y confección',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 12240,
                'id_tipo' => 5535,
                'id_centro' => 22,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Soldadura',
                'descripcion' => 'Soldadura',
                'competencias_adquiridas' => '',
                'duracion' => 220,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 12241,
                'id_tipo' => 5535,
                'id_centro' => 22,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Panadería y bollería',
                'descripcion' => 'Panadería y bollería',
                'competencias_adquiridas' => '',
                'duracion' => 220,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 12242,
                'id_tipo' => 5535,
                'id_centro' => 22,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte de cabello y barbería',
                'descripcion' => 'Corte de cabello y barbería',
                'competencias_adquiridas' => '',
                'duracion' => 90,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 12243,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación de motores de combustión interna',
                'descripcion' => 'Reparación de motores de combustión interna',
                'competencias_adquiridas' => '',
                'duracion' => 212,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 12244,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Electricidad automotriz',
                'descripcion' => 'Electricidad automotriz',
                'competencias_adquiridas' => '',
                'duracion' => 280,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 12245,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Electricidad residencial',
                'descripcion' => 'Electricidad residencial',
                'competencias_adquiridas' => '',
                'duracion' => 184,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 12246,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Soldadura oxiacetilénica, oxicorte y corte con plasma',
                'descripcion' => 'Soldadura oxiacetilénica, oxicorte y corte con plasma',
                'competencias_adquiridas' => '',
                'duracion' => 0,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 12247,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Mantenimiento equipos línea marrón',
                'descripcion' => 'Mantenimiento equipos línea marrón',
                'competencias_adquiridas' => '',
                'duracion' => 0,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12248,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Mantenimiento y reparación de sistemas de mandos',
                'descripcion' => 'Mantenimiento y reparación de sistemas de mandos',
                'competencias_adquiridas' => '',
                'duracion' => 0,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 12249,
                'id_tipo' => 5535,
                'id_centro' => 17,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección prendas damas y caballero',
                'descripcion' => 'Corte y confección prendas damas y caballero',
                'competencias_adquiridas' => '',
                'duracion' => 288,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 12250,
                'id_tipo' => 5535,
                'id_centro' => 17,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección con técnicas creativas e innovadoras',
                'descripcion' => 'Corte y confección con técnicas creativas e innovadoras',
                'competencias_adquiridas' => '',
                'duracion' => 159,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 12251,
                'id_tipo' => 5535,
                'id_centro' => 17,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección uniformes escolares y trajes folclóricos',
                'descripcion' => 'Corte y confección uniformes escolares y trajes folclóricos',
                'competencias_adquiridas' => '',
                'duracion' => 134,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 12252,
                'id_tipo' => 5535,
                'id_centro' => 17,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección prendas para caballeros',
                'descripcion' => 'Corte y confección prendas para caballeros',
                'competencias_adquiridas' => '',
                'duracion' => 164,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 12253,
                'id_tipo' => 5535,
                'id_centro' => 24,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Mantenimiento preventivo básico maquinaria pesada',
                'descripcion' => 'Mantenimiento preventivo básico maquinaria pesada',
                'competencias_adquiridas' => '',
                'duracion' => 112,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 12254,
                'id_tipo' => 5535,
                'id_centro' => 24,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Operador profesional de excavadora hidráulica',
                'descripcion' => 'Operador profesional de excavadora hidráulica',
                'competencias_adquiridas' => '',
                'duracion' => 56,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 12255,
                'id_tipo' => 5535,
                'id_centro' => 24,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Operador básico de retroexcavadora cargadora nivel',
                'descripcion' => 'Operador básico de retroexcavadora cargadora nivel',
                'competencias_adquiridas' => '',
                'duracion' => 144,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 12256,
                'id_tipo' => 5535,
                'id_centro' => 22,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Soldadura de estructuras livianas',
                'descripcion' => 'Soldadura de estructuras livianas',
                'competencias_adquiridas' => '',
                'duracion' => 220,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 12257,
                'id_tipo' => 5535,
                'id_centro' => 22,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación y mantenimiento de motocicleta',
                'descripcion' => 'Reparación y mantenimiento de motocicleta',
                'competencias_adquiridas' => '',
                'duracion' => 240,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 12258,
                'id_tipo' => 5535,
                'id_centro' => 22,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Torno',
                'descripcion' => 'Torno',
                'competencias_adquiridas' => '',
                'duracion' => 300,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 12259,
                'id_tipo' => 5535,
                'id_centro' => 13,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación de sistema de suspensión y dirección',
                'descripcion' => 'Reparación de sistema de suspensión y dirección',
                'competencias_adquiridas' => '',
                'duracion' => 190,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 12260,
                'id_tipo' => 5535,
                'id_centro' => 13,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Mantenimiento y reparación de sistemas de mandos',
                'descripcion' => 'Mantenimiento y reparación de sistemas de mandos',
                'competencias_adquiridas' => '',
                'duracion' => 0,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 12261,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5613,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Operaciones auxiliares de recepción y arreglo de habitaciones',
                'descripcion' => 'Operaciones auxiliares de recepción y arreglo de habitaciones',
                'competencias_adquiridas' => '',
                'duracion' => 192,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 12262,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5608,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Cocina básica',
                'descripcion' => 'Cocina básica',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 12263,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5608,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Servicios de bar y restaurante',
                'descripcion' => 'Servicios de bar y restaurante',
                'competencias_adquiridas' => '',
                'duracion' => 232,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 12264,
                'id_tipo' => 5535,
                'id_centro' => 10,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Soldadura estructura liviana',
                'descripcion' => 'Soldadura estructura liviana',
                'competencias_adquiridas' => '',
                'duracion' => 230,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 12265,
                'id_tipo' => 5535,
                'id_centro' => 26,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación y mantenimiento de motocicleta',
                'descripcion' => 'Reparación y mantenimiento de motocicleta',
                'competencias_adquiridas' => '',
                'duracion' => 240,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 12266,
                'id_tipo' => 5535,
                'id_centro' => 11,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Asistente de tienda de conveniencia',
                'descripcion' => 'Asistente de tienda de conveniencia',
                'competencias_adquiridas' => '',
                'duracion' => 160,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 12267,
                'id_tipo' => 5535,
                'id_centro' => 11,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Encargado de tienda de conveniencia',
                'descripcion' => 'Encargado de tienda de conveniencia',
                'competencias_adquiridas' => '',
                'duracion' => 168,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 12268,
                'id_tipo' => 5535,
                'id_centro' => 11,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Elaboración de conservas y derivados lácteos',
                'descripcion' => 'Elaboración de conservas y derivados lácteos',
                'competencias_adquiridas' => '',
                'duracion' => 172,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 12269,
                'id_tipo' => 5535,
                'id_centro' => 23,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación y mantenimiento de motocicleta',
                'descripcion' => 'Reparación y mantenimiento de motocicleta',
                'competencias_adquiridas' => '',
                'duracion' => 240,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 12270,
                'id_tipo' => 5535,
                'id_centro' => 23,
                'id_sector' => 5613,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Técnicas de servicio al huésped',
                'descripcion' => 'Técnicas de servicio al huésped',
                'competencias_adquiridas' => '',
                'duracion' => 132,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 12271,
                'id_tipo' => 5535,
                'id_centro' => 20,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Apicultura y diversificación de fincas',
                'descripcion' => 'Apicultura y diversificación de fincas',
                'competencias_adquiridas' => '',
                'duracion' => 120,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 12272,
                'id_tipo' => 5535,
                'id_centro' => 20,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Procesamiento artesanal de frutas, vegetales y dulces',
                'descripcion' => 'Procesamiento artesanal de frutas, vegetales y dulces',
                'competencias_adquiridas' => '',
                'duracion' => 120,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 12273,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Electricidad automotriz',
                'descripcion' => 'Electricidad automotriz',
                'competencias_adquiridas' => '',
                'duracion' => 280,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 12274,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Electricidad residencial',
                'descripcion' => 'Electricidad residencial',
                'competencias_adquiridas' => '',
                'duracion' => 184,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 12275,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Mecánica básica automotriz',
                'descripcion' => 'Mecánica básica automotriz',
                'competencias_adquiridas' => '',
                'duracion' => 116,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 12276,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Soldadura básica eléctrica y autógena',
                'descripcion' => 'Soldadura básica eléctrica y autógena',
                'competencias_adquiridas' => '',
                'duracion' => 116,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 12277,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Climatización automotriz',
                'descripcion' => 'Climatización automotriz',
                'competencias_adquiridas' => '',
                'duracion' => 116,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 12278,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Mantenimiento electrodomésticos línea gris',
                'descripcion' => 'Mantenimiento electrodomésticos línea gris',
                'competencias_adquiridas' => '',
                'duracion' => 156,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 12279,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación de motores de combustión interna',
                'descripcion' => 'Reparación de motores de combustión interna',
                'competencias_adquiridas' => '',
                'duracion' => 212,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 12280,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Tornero básico',
                'descripcion' => 'Tornero básico',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 12281,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Control y arranque de motores trifásicos',
                'descripcion' => 'Control y arranque de motores trifásicos',
                'competencias_adquiridas' => '',
                'duracion' => 156,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 12282,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación y mantenimiento de motocicleta',
                'descripcion' => 'Reparación y mantenimiento de motocicleta',
                'competencias_adquiridas' => '',
                'duracion' => 240,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 12283,
                'id_tipo' => 5535,
                'id_centro' => 19,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Soldadura especializada MIG y TIC',
                'descripcion' => 'Soldadura especializada MIG y TIC',
                'competencias_adquiridas' => '',
                'duracion' => 116,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 12284,
                'id_tipo' => 5535,
                'id_centro' => 15,
                'id_sector' => 5608,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Cocina nicaragüense',
                'descripcion' => 'Cocina nicaragüense',
                'competencias_adquiridas' => '',
                'duracion' => 100,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 12285,
                'id_tipo' => 5535,
                'id_centro' => 15,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección prendas para dama',
                'descripcion' => 'Corte y confección prendas para dama',
                'competencias_adquiridas' => '',
                'duracion' => 150,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 12286,
                'id_tipo' => 5535,
                'id_centro' => 14,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección',
                'descripcion' => 'Corte y confección',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 12287,
                'id_tipo' => 5535,
                'id_centro' => 14,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Electrónica básica',
                'descripcion' => 'Electrónica básica',
                'competencias_adquiridas' => '',
                'duracion' => 204,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 12288,
                'id_tipo' => 5535,
                'id_centro' => 21,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección',
                'descripcion' => 'Corte y confección',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 12289,
                'id_tipo' => 5535,
                'id_centro' => 21,
                'id_sector' => 5500,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Electricidad residencial',
                'descripcion' => 'Electricidad residencial',
                'competencias_adquiridas' => '',
                'duracion' => 184,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 12290,
                'id_tipo' => 5535,
                'id_centro' => 21,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Reparación y mantenimiento de computadoras',
                'descripcion' => 'Reparación y mantenimiento de computadoras',
                'competencias_adquiridas' => '',
                'duracion' => 100,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 12291,
                'id_tipo' => 5535,
                'id_centro' => 16,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Elaboración de néctares y conserva',
                'descripcion' => 'Elaboración de néctares y conserva',
                'competencias_adquiridas' => '',
                'duracion' => 125,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 12292,
                'id_tipo' => 5535,
                'id_centro' => 16,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Apicultura tropical',
                'descripcion' => 'Apicultura tropical',
                'competencias_adquiridas' => '',
                'duracion' => 144,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 12293,
                'id_tipo' => 5535,
                'id_centro' => 16,
                'id_sector' => 5608,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Cocina básica',
                'descripcion' => 'Cocina básica',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 12294,
                'id_tipo' => 5535,
                'id_centro' => 16,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Producción artesanal de alimentos',
                'descripcion' => 'Producción artesanal de alimentos',
                'competencias_adquiridas' => '',
                'duracion' => 118,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 12295,
                'id_tipo' => 5535,
                'id_centro' => 12,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Apicultura tropical énfasis BPA',
                'descripcion' => 'Apicultura tropical énfasis BPA',
                'competencias_adquiridas' => '',
                'duracion' => 104,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 12296,
                'id_tipo' => 5535,
                'id_centro' => 12,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Valor agregado producción de miel y otros productos de la colmena',
                'descripcion' => 'Valor agregado producción de miel y otros productos de la colmena',
                'competencias_adquiridas' => '',
                'duracion' => 64,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 12297,
                'id_tipo' => 5535,
                'id_centro' => 27,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
            'nombre' => 'Especialización en MSS/SOC (análisis de riesgo cibernético)',
            'descripcion' => 'Especialización en MSS/SOC (análisis de riesgo cibernético)',
                'competencias_adquiridas' => '',
                'duracion' => 190,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 12298,
                'id_tipo' => 5535,
                'id_centro' => 27,
                'id_sector' => 5609,
                'id_unidad_duracion' => 5523,
            'nombre' => 'Especialización en hacking ético (gestión de vulnerabilidad)',
            'descripcion' => 'Especialización en hacking ético (gestión de vulnerabilidad)',
                'competencias_adquiridas' => '',
                'duracion' => 190,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 12299,
                'id_tipo' => 5535,
                'id_centro' => 18,
                'id_sector' => 5608,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Cocina básica',
                'descripcion' => 'Cocina básica',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 12300,
                'id_tipo' => 5535,
                'id_centro' => 18,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección',
                'descripcion' => 'Corte y confección',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 12301,
                'id_tipo' => 5535,
                'id_centro' => 18,
                'id_sector' => 5613,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Alojamiento',
                'descripcion' => 'Alojamiento',
                'competencias_adquiridas' => '',
                'duracion' => 120,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 12302,
                'id_tipo' => 5535,
                'id_centro' => 18,
                'id_sector' => 5580,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Panadería y bollería',
                'descripcion' => 'Panadería y bollería',
                'competencias_adquiridas' => '',
                'duracion' => 220,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 12303,
                'id_tipo' => 5535,
                'id_centro' => 13,
                'id_sector' => 5499,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Sistema de carga y arranque',
                'descripcion' => 'Sistema de carga y arranque',
                'competencias_adquiridas' => '',
                'duracion' => 80,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 12304,
                'id_tipo' => 5535,
                'id_centro' => 15,
                'id_sector' => 5573,
                'id_unidad_duracion' => 5523,
                'nombre' => 'Corte y confección',
                'descripcion' => 'Corte y confección',
                'competencias_adquiridas' => '',
                'duracion' => 140,
                'created_at' => '2020-03-06 11:51:28',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}