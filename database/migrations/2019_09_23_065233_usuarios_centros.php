<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsuariosCentros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_centros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario')->unsigned();
            $table->integer('id_centro')->unsigned();
            $table->timestamps();
        });

        Schema::table('usuarios_centros', function (Blueprint $table) {
              //Laves foraneas
              $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('RESTRICT');
              $table->foreign('id_centro')->references('id')->on('centros')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios_centros', function (Blueprint $table) {
            $table->dropForeign('id_usuario');
            $table->dropForeign('id_centro');
        });
        Schema::dropIfExists('usuarios_centros');
    }
}
