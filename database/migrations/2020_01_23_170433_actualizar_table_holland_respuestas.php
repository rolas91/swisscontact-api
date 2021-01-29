<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActualizarTableHollandRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holland_respuesta', function (Blueprint $table) {
            $table->integer('id_holland_test')->unsigned()->comment('se agrega la columna foreing key a la tabla holland_tests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holland_respuesta', function (Blueprint $table) {
            $table->dropColumn('id_holland_test');
        });
    }
}
