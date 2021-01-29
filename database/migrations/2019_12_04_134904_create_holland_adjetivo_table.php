<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHollandAdjetivoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('holland_adjetivo', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->primary();
			$table->string('texto', 50);
			$table->string('dimension', 1);
			$table->dateTime('creado');
			$table->dateTime('actualizado');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('holland_adjetivo');
	}

}
