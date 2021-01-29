<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaReportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('reportes', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('id_datasource')->unsigned()->comment('el id de la fuente de datos del reporte');
           $table->integer('id_usuario')->unsigned()->comment('id_usuario que crea el reporte');
           $table->string('nombre');
           $table->text('configuracion')->nullable()->comment('configuracion del pivot table en json en caso que sea el reporte dinamico');
           $table->timestamps();
       });

       Schema::table('reportes', function (Blueprint $table) {
        //Laves foraneas
        $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('RESTRICT');
  });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropForeign('id_usuario');
        });


        Schema::dropIfExists('reportes');
    }
}
