<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlavesForaneasATablaInstructores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructores', function (Blueprint $table) {
            $table->foreign('id_pais')->references('id')->on('catalogos_detalles');
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->foreign('id_departamento')->references('id')->on('catalogos_detalles');
            $table->foreign('id_municipio')->references('id')->on('catalogos_detalles');
            $table->foreign('id_nivel_academico')->references('id')->on('catalogos_detalles');
            $table->foreign('id_tipo_identificacion')->references('id')->on('catalogos_detalles');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructores', function (Blueprint $table) {
            $table->dropForeign('id_pais');
            $table->dropForeign('id_usuario');
            $table->dropForeign('id_departamento');
            $table->dropForeign('id_municipio');
            $table->dropForeign('id_nivel_academico');
            $table->dropForeign('id_tipo_identificacion');
          
        });
    }
}
