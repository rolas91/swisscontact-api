<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCursosInstructores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos_instructores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_curso')->unsigned();
            $table->integer('id_instructor')->unsigned();
            $table->timestamps();
        });

        Schema::table('cursos_instructores', function (Blueprint $table) {
              //Laves foraneas
              $table->foreign('id_curso')->references('id')->on('cursos')->onDelete('RESTRICT');
              $table->foreign('id_instructor')->references('id')->on('instructores')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cursos_instructores', function (Blueprint $table) {
            $table->dropForeign('id_curso');
            $table->dropForeign('id_instructor');
        });
        Schema::dropIfExists('cursos_instructores');
    }
}
