<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCursosMatriculasAddTestHolland extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cursos_matriculas', function (Blueprint $table) {
            $table->integer('id_test_holland')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cursos_matriculas', function (Blueprint $table) {
            $table->dropColumn('id_test_holland');
        });
    }
}
