<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bitacora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('bitacora', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('user_id')->unsigned()->nullable()->comment('id del usuario que realiza la accion');
           $table->string('action')->comment('accion a aplicada');
           $table->string('model')->comment('modelo al que hace referencia la accion, ej: curso,usuario,centro, etc');
           $table->integer('id_model')->nullable()->comment('id del modelo, por ejemplo id del curso');
           $table->string('ip_address')->nullable()->comment('id desde la que se conecta el usuario que realiza la accion');
           $table->string('user_agent')->nullable()->comment('el agent desde el que se realiza la accion por ejemplo, el navegador,android, etc');
           $table->string('url')->nullable()->comment('url desde la que se realiza la accion');
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
        Schema::dropIfExists('bitacora');
    }
}
