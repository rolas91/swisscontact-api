<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CrearTablaTemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('color')->default('black');
            $table->string('font-family')->default('sans-serif');
            $table->string('font-size')->default('16px');
            $table->string('font-weight')->default('normal');
            $table->String('background-color')->nullable();
            $table->text('background-image')->nullable();
            
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
        Schema::dropIfExists('temas');
    }
}
