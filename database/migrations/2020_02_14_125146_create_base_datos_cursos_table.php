<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBaseDatosCursosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('base_datos_cursos', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('Nombre_del_curso')->nullable();
			$table->string('Sector')->nullable();
			$table->string('Nombre_del_centro')->nullable();
			$table->string('municipio')->nullable();
			$table->string('depto')->nullable();
			$table->integer('carga_horaria')->nullable();
			$table->string('inicio')->nullable();
			$table->string('fin')->nullable();
			$table->integer('ao')->nullable();
			$table->float('costo_por_participante_USD', 10, 0)->nullable();
			$table->string('Nombre_del_instructor')->nullable();
			$table->integer('cupos_por_curso')->nullable();
			$table->integer('H')->nullable();
			$table->integer('M')->nullable();
			$table->integer('total')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('base_datos_cursos');
	}

}
