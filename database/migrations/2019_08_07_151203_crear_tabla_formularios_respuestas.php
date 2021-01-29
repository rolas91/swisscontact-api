<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaFormulariosRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formularios_respuestas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_formulario')->unsigned()->comment('id del formulario al que pertenece la respuesta');
            $table->integer('id_participante')->nullable()->unsigned()->comment('id del participante que llena el formulario');
            $table->string('nombre_participante')->nullable()->comment('en caso de ser un formulario anónimo, permitimos escribir el nombre del participante');
            $table->string('correo_participante')->nullable()->comment('En caso de ser un formulario anonimo, permitimos escribir el correo del participante');
            $table->integer('id_centro')->unsigned()->nullable()->comment('Vincula el formulario a un centro en especifico');
            $table->integer('id_curso')->unsigned()->nullable()->comment('Vincula el formulario a un curso especifico');
            $table->integer('id_evaluador')->unsigned()->nullable()->comment('en caso que un usuario manualmente evalue las respuestas, se guardar el usuario evaluador');
            $table->dateTime('fecha_inicio')->comment('fecha y hora en que se inicia a llenar el formulario');
            $table->dateTime('fecha_fin')->nullable()->comment('fecha y hora en que termina de llenar el formulario');
            $table->string('duracion')->default(0)->comment('el tiempo que tarda la persona que responde el formulario');
            $table->decimal('nota')->default(0)->comment('nota obtenida por el usuario al llenar el examen o formulario');
            $table->boolean('estado')->default(1)->comment('el estado de la respuesta en caso de completado, interrumpido');
            $table->string('plataforma', 5)->default('web')->comment('este campo es para llevar registro si la respuesta del formulario fue llenado en la plataforma web o en el movil.');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('formularios_respuestas', function (Blueprint $table) {
            $table->foreign('id_formulario')->references('id')->on('formularios')->onDelete('RESTRICT');
            $table->foreign('id_participante')->references('id')->on('participantes')->onDelete('RESTRICT');
            $table->foreign('id_centro')->references('id')->on('centros')->onDelete('RESTRICT');
            $table->foreign('id_curso')->references('id')->on('cursos')->onDelete('RESTRICT');
            $table->foreign('id_evaluador')->references('id')->on('usuarios')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formularios_respuestas', function (Blueprint $table) {
            $table->dropForeign('id_formulario');
            $table->dropForeign('id_participante');
            $table->dropForeign('id_evaluador');
        });
        Schema::dropIfExists('formularios_respuestas');
    }
}
