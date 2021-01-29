<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollandRespuestaAdjetivosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('holland_respuesta_adjetivos', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->integer('respuesta_id')->index('holland_respuesta_adjetivos_respuesta_id_16cfe88d');
			$table->integer('adjetivo_id')->index('holland_respuesta_adjetivos_adjetivos_id_f6e6075b');
			$table->unique(['respuesta_id','adjetivo_id'], 'holland_respuesta_adjeti_respuesta_id_adjetivos_i_75cca5f2_uniq');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('holland_respuesta_adjetivos');
	}

}
