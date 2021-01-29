<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBaseDatosConsolidada20182019Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('base_datos_consolidada_2018_2019', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('fecha_encuesta')->nullable();
			$table->string('n_participante')->nullable();
			$table->string('cedula')->nullable();
			$table->string('fecha_nacimiento')->nullable();
			$table->integer('edad')->nullable();
			$table->string('est_civil')->nullable();
			$table->string('sexo')->nullable();
			$table->string('tel_partic')->nullable();
			$table->string('direccion')->nullable();
			$table->string('municipio')->nullable();
			$table->string('depto')->nullable();
			$table->string('persona_adic')->nullable();
			$table->string('parentesco')->nullable();
			$table->string('tel_per2')->nullable();
			$table->string('grado')->nullable();
			$table->string('nivel')->nullable();
			$table->string('centro')->nullable();
			$table->string('curso')->nullable();
			$table->string('inicio')->nullable();
			$table->string('fin')->nullable();
			$table->string('calidad')->nullable();
			$table->string('trabaja')->nullable();
			$table->string('lugar_trabajo')->nullable();
			$table->string('nombre')->nullable();
			$table->string('tel_trab')->nullable();
			$table->string('direcc_trabajo')->nullable();
			$table->string('activ_econ')->nullable();
			$table->string('cargo_trab')->nullable();
			$table->string('cargo_relcurso')->nullable();
			$table->string('ingreso_mes')->nullable();
			$table->float('antig_trab', 10, 0)->nullable();
			$table->integer('trab_diasem')->nullable();
			$table->float('trab_hrdia', 10, 0)->nullable();
			$table->string('motivacion_trabaja')->nullable();
			$table->string('motivac_no_trabaja')->nullable();
			$table->integer('NPS')->nullable();
			$table->string('perodo')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('base_datos_consolidada_2018_2019');
	}

}
