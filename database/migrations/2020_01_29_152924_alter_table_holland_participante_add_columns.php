<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHollandParticipanteAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holland_participante', function (Blueprint $table) {
            $table->string('nombres', 50)->comment('nombres del participante');
            $table->string('apellidos', 50)->comment('apellidos del participante');
            $table->string('personalidad', 15)->change();
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
            $table->dropColumn('nombres');
            $table->dropColumn('apellidos');
            $table->string('personalidad', 1)->change();
        });
    }
}
