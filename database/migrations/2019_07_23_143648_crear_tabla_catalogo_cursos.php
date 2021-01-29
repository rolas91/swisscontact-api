<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCatalogoCursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_cursos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tipo')->unsigned()->comment('tipo del curso: programa,curso,talleres, seminarios');
            $table->integer('id_centro')->unsigned()->comment('centro al que pertenece el curso');
            $table->integer('id_sector')->unsigned()->comment('sector economico que aborda el curso, por ejemplo: Informatica, Mecanica, etc,');
            $table->integer('id_unidad_duracion')->unsigned()->comment('unidad de duracion del curso ej: meses,semanas,dias,etc, por defecto en horas');
            $table->string('nombre',500)->comment('nombre del curso');
            $table->string('descripcion', 2000)->nullable()->comment('descripcion del curso');
            $table->string('competencias_adquiridas', 2000)->nullable()->comment('Competencias a adquirir');
            $table->integer('duracion')->unsigned()->comment('duracion del curso');
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
        Schema::dropIfExists('catalogo_cursos');
    }
}
