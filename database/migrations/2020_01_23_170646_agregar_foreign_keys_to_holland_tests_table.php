<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarForeignKeysToHollandTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holland_respuesta', function (Blueprint $table) {
            $table->foreign('id_holland_test')->references('id')->on('holland_tests')->onDelete('RESTRICT');
        });

        Schema::table('holland_tests', function (Blueprint $table) {
            $table->foreign('usuario_creacion')->references('id')->on('usuarios')->onDelete('RESTRICT');
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
            $table->dropForeign('id_holland_test');
        });

        Schema::table('holland_tests', function (Blueprint $table) {
            $table->dropForeign('usuario_creacion');
        });
    }
}
