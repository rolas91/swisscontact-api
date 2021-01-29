<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlavesForaneasATablaCursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

         Schema::table('cursos', function (Blueprint $table) {
            $table->foreign('id_curso')->references('id')->on('catalogo_cursos');
            $table->foreign('id_modalidad')->references('id')->on('catalogos_detalles');
            $table->foreign('id_modo')->references('id')->on('catalogos_detalles');
            $table->foreign('id_estado')->references('id')->on('catalogos_detalles');
            $table->foreign('id_pais')->references('id')->on('catalogos_detalles');
            $table->foreign('id_departamento')->references('id')->on('catalogos_detalles');
            $table->foreign('id_municipio')->references('id')->on('catalogos_detalles');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('cursos', function (Blueprint $table) {
            $table->dropForeign('id_curso');
            $table->dropForeign('id_modalidad');
            $table->dropForeign('id_modo');
            $table->dropForeign('id_estado');
            $table->dropForeign('id_pais');
            $table->dropForeign('id_departamento');
            $table->dropForeign('id_municipio');
           
        });
     
    }
}
