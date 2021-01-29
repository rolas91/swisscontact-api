<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlavesForaneasATablaCatalogoCursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_cursos', function (Blueprint $table) {
            $table->foreign('id_tipo')->references('id')->on('catalogos_detalles');
            $table->foreign('id_centro')->references('id')->on('centros');
            $table->foreign('id_sector')->references('id')->on('catalogos_detalles');
            $table->foreign('id_unidad_duracion')->references('id')->on('catalogos_detalles');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_cursos', function (Blueprint $table) {
            $table->dropForeign('id_tipo');
            $table->dropForeign('id_centro');
            $table->dropForeign('id_sector');
            $table->dropForeign('id_pais');
            $table->dropForeign('id_departamento');
            $table->dropForeign('id_municipio');
            $table->dropForeign('id_unidad_duracion');
           
        });
    }
}
