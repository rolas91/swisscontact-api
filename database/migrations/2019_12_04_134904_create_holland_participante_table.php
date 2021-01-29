<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollandParticipanteTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holland_participante', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->dateTime('creado');
            $table->dateTime('actualizado');
            $table->integer('test_id');
            $table->integer('id_participante')->nullable();
            $table->string('correo', 250)->nullable();
            $table->string('token', 250)->nullable();
            $table->dateTime('invitacion_enviada')->nullable();
            $table->string('personalidad', 1);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('holland_participante');
    }
}
