<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlavesForaneasATablaParticipantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table( 'participantes', function (Blueprint $table) {
            $table->foreign('id_tipo_identificacion')->references('id')->on('catalogos_detalles');
            $table->foreign('id_estado_civil')->references('id')->on('catalogos_detalles');
            $table->foreign('id_pais')->references('id')->on('catalogos_detalles');
            $table->foreign('id_departamento')->references('id')->on('catalogos_detalles');
            $table->foreign('id_ciudad')->references('id')->on('catalogos_detalles');
            $table->foreign('id_nivel_educacion')->references('id')->on('catalogos_detalles');
        
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'participantes', function (Blueprint $table) {
            $table->dropForeign('id_tipo_identificacion');
            $table->dropForeign('id_estado_civil');
            $table->dropForeign('id_pais');
            $table->dropForeign('id_departamento');
            $table->dropForeign('id_ciudad');
            $table->dropForeign('id_nivel_educacion');
        });
    }
}
