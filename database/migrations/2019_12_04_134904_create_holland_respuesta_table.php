<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollandRespuestaTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holland_respuesta', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('creado');
            $table->dateTime('actualizado');
            $table->integer('tiempo');
            $table->integer('participante_id')->nullable();
            $table->text('parte_b', 65535)->nullable();
            $table->text('parte_c', 65535)->nullable();
            $table->string('parte_d_1', 1)->nullable();
            $table->string('parte_d_2', 1)->nullable();
            $table->string('parte_d_3', 1)->nullable();
            $table->string('parte_d_4', 1)->nullable();
            $table->string('parte_d_5', 1)->nullable();
            $table->integer('test_id')->nullable();
            $table->text('cuadro_resumen', 65535)->nullable();
            $table->dateTime('hora_finalizado')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('holland_respuesta');
    }
}
