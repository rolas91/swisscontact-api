<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlavesForaneasATablaCursosMatriculas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cursos_matriculas', function (Blueprint $table) {
            $table->foreign('id_curso')->references('id')->on('cursos');
            $table->foreign('id_participante')->references('id')->on('participantes');
            $table->foreign('id_tipo_identificacion')->references('id')->on('catalogos_detalles');
            $table->foreign('id_estado_civil')->references('id')->on('catalogos_detalles');
            $table->foreign('id_pais')->references('id')->on('catalogos_detalles');
            $table->foreign('id_departamento')->references('id')->on('catalogos_detalles');
            $table->foreign('id_municipio')->references('id')->on('catalogos_detalles');
            $table->foreign('id_nivel_academico')->references('id')->on('catalogos_detalles');
            $table->foreign('id_parentezco')->references('id')->on('catalogos_detalles');
           

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cursos_matriculas', function (Blueprint $table) {
            $table->dropForeign('id_curso');
            $table->dropForeign('id_participante');
            $table->dropForeign('id_tipo_identificacion');
            $table->dropForeign('id_estado_civil');
            $table->dropForeign('id_pais');
            $table->dropForeign('id_departamento');
            $table->dropForeign('id_municipio');
            $table->dropForeign('id_nivel_academico');
            $table->dropForeign('id_parentezco');
        });
    }
}
