<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCentrosPresupuesto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros_presupuesto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_centro')->unsigned();
            $table->date('desde');
            $table->date('hasta');
            $table->decimal('monto',18,2);
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
        Schema::dropIfExists('centros_presupuesto');
    }
}
