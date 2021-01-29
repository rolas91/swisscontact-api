<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaReportesUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_reporte')->unsigned();
            $table->integer('id_usuario')->unsigned();
            $table->timestamps();
        });

        Schema::table('reportes_usuarios', function (Blueprint $table) {
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('RESTRICT');
            $table->foreign('id_reporte')->references('id')->on('reportes')->onDelete('RESTRICT');
      });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('id_usuario');
            $table->dropForeign('id_reporte');
        });
        Schema::dropIfExists('reportes_usuarios');
    }
}
