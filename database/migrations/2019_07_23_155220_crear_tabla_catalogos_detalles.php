<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCatalogosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogos_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo', 50);
            $table->string('nombre');
            $table->integer('id_catalogo');
            $table->string('descripcion');
            $table->string('valor')->nullable();
            $table->boolean('activo');
            $table->integer('id_padre')->nullable();
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
        Schema::dropIfExists('catalogos_detalles');
    }
}
