<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsuariosTableSeeder::class);
        $this->call(CatalogosTableSeeder::class);
        $this->call(CatalogosDetallesTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
        $this->call(CentrosTableSeeder::class);
      
        $this->call(InstructoresTableSeeder::class);
        $this->call(ParticipantesTableSeeder::class);
        $this->call(CatalogoCursosTableSeeder::class);
        $this->call(CursosTableSeeder::class);
        $this->call(CursosMatriculasTableSeeder::class);
        $this->call(AccesosTableSeeder::class);
        $this->call(RolesAccesosTableSeeder::class);
        $this->call(CursosInstructoresTableSeeder::class);
        $this->call(UsuariosCentrosTableSeeder::class);
        $this->call(FormulariosTableSeeder::class);
        $this->call(FormulariosSeccionesTableSeeder::class);
        $this->call(FormulariosCamposTableSeeder::class);
        $this->call(FormulariosRespuestasTableSeeder::class);
        $this->call(FormulariosRespuestasCamposTableSeeder::class);
        $this->call(HollandAdjetivoTableSeeder::class);
        $this->call(BitacoraTableSeeder::class);
        $this->call(HollandTestsTableSeeder::class);
        $this->call(HollandRespuestaTableSeeder::class);
        $this->call(HollandRespuestaAdjetivosTableSeeder::class);
        $this->call(ReportesTableSeeder::class);
        $this->call(HollandParticipanteTableSeeder::class);
        $this->call(BaseDatosConsolidada20182019TableSeeder::class);
        $this->call(BaseDatosCursosTableSeeder::class);
    }
}
