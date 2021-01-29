<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarLlavesATablaCorreosEnviados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('correos_enviados', function (Blueprint $table) {
            $table->foreign('id_curso')->references('id')->on('cursos')->onDelete('RESTRICT');
            $table->foreign('id_centro')->references('id')->on('centros')->onDelete('RESTRICT');
            $table->foreign('id_participante')->references('id')->on('participantes')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('correos_enviados', function (Blueprint $table) {
            $table->dropForeign('id_curso');
            $table->dropForeign('id_centro');
            $table->dropForeign('id_participante');
        });
    }
}
