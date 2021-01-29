<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTableHollandTestsCentros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holland_tests_centros', function (Blueprint $table) {
            $table->foreign('centro_id')->references('id')->on('centros')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('holland_tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holland_tests_centros', function (Blueprint $table) {
            $table->dropForeign('centro_id');
            $table->dropForeign('test_id');
        });
    }
}
