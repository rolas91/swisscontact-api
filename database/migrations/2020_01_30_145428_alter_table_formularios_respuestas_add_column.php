<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFormulariosRespuestasAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formularios_respuestas', function (Blueprint $table) {
            $table->string('slug', 16)->nullable()->comment('slug random de la respuesta ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formularios_respuestas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
