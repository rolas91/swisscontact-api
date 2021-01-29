<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToHollandRespuestaAdjetivosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('holland_respuesta_adjetivos', function(Blueprint $table)
		{
			$table->foreign('respuesta_id', 'holland_respuesta_ad_respuesta_id_16cfe88d_fk_holland_r')->references('id')->on('holland_respuesta')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('holland_respuesta_adjetivos', function(Blueprint $table)
		{
			$table->dropForeign('holland_respuesta_ad_respuesta_id_16cfe88d_fk_holland_r');
		});
	}

}
