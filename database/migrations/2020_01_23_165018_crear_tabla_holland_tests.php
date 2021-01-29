<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaHollandTests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holland_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->date('fecha_inicio')->comment('Fecha desde la que estará disponible el test');
            $table->date('fecha_fin')->comment('Fecha en la que finaliza la disponibilidad del test');
            $table->integer('usuario_creacion')->unsigned()->comment('usuario que crea el test');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('holland_tests');
    }
}
