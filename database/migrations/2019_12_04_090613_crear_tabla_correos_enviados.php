<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCorreosEnviados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correos_enviados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_curso')->unsigned()->nullable()->comment('id del curso en caso de ser un participante');
            $table->integer('id_centro')->unsigned()->nullable()->comment('id del centro en caso de ser un participante');
            $table->integer('id_participante')->unsigned()->nullable()->comment('id del participante');
            $table->string('correo',150)->comment('correo del participante');
            $table->string('formulario',150)->comment('Url del formulario');
            $table->timestamps();
        });


        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('correos_enviados');
    }
}
