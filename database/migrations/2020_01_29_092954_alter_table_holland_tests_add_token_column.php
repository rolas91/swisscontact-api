<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHollandTestsAddTokenColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holland_tests', function (Blueprint $table) {
            $table->string('token', 50)
            ->unique()
            ->after('usuario_creacion')
            ->comment('se agrega el campo token para permitir url unica del formulario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holland_tests', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
}
