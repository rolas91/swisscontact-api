<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHollandParticipanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holland_participante', function (Blueprint $table) {
            $table->string('telefono', 25)->nullable();
            $table->string('cedula', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holland_participante', function (Blueprint $table) {
            $table->dropColumn('telefono');
            $table->dropColumn('cedula');
        });
    }
}
