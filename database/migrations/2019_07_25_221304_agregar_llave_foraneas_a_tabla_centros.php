<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlaveForaneasATablaCentros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centros', function (Blueprint $table) {
            $table->foreign('id_pais')->references('id')->on('catalogos_detalles');
            $table->foreign('id_tipo')->references('id')->on('catalogos_detalles');
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
        Schema::table('centros', function (Blueprint $table) {
            $table->dropForeign('id_pais');
            $table->dropForeign('id_tipo');
            $table->dropForeign('id_departamento');
            $table->dropForeign('id_municipio');
        });
    }
}
