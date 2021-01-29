<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaFormulariosSecciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formularios_secciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_formulario')->unsigned()->comment('id del formulario al que pertenece la seccion');
            $table->string('titulo',500)->nullable()->comment('Titulo del formulario, esto ya es de manera visual en caso que se quiera añadir algun texto para explicar como llenar el formulario');
            $table->text('descripcion')->nullable()->comment('descripcion del formulario');
            $table->timestamps();
        });

        Schema::table('formularios_secciones', function (Blueprint $table) {
            $table->foreign('id_formulario')->references('id')->on('formularios')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('formularios_secciones', function (Blueprint $table) {
            $table->dropForeign('id_formulario');
        });
        Schema::dropIfExists('formularios_secciones');
    }
}
