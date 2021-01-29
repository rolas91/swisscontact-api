<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaFormulariosRespuestasCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formularios_respuestas_campos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_formulario_respuesta')->unsigned()->comment('id del formulario respuesta (header)');
            $table->integer('id_formulario_campo')->unsigned()->comment('id del campo al que pertenece la respuesta');
            $table->text('valor')->nullable()->comment('valor de la respuesta escrita o seleccionada por el usuario');
            $table->integer('nota')->default(0)->comment('la nota o puntaje obtenido en la contestacion del formulario ');
            $table->boolean('evaluada')->default(false)->comment('si el campo ya ha sido revisado');
            $table->timestamps();   
        });

        Schema::table('formularios_respuestas_campos', function (Blueprint $table) {
            $table->foreign('id_formulario_respuesta')->references('id')->on('formularios_respuestas')->onDelete('RESTRICT');
            $table->foreign('id_formulario_campo')->references('id')->on('formularios_campos')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('id_formulario_campo');
            $table->dropForeign('id_formulario_respuesta');
        });
        Schema::dropIfExists('formularios_respuestas_campos');
    }
}
