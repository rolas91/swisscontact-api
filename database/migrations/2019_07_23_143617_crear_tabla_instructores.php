<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaInstructores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario')->unsigned();
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('telefono_1', 20)->nullable()->comment('telefono claro');
            $table->string('telefono_2', 20)->nullable()->comment('telefono movistar');
            $table->string('telefono_otro', 20)->nullable()->comment('cualquier otro telefono o cualquier otra compañia telefonica');
            $table->integer('id_pais')->unsigned();
            $table->integer('id_departamento')->unsigned();
            $table->integer('id_municipio')->unsigned();
            $table->char('sexo')->comment('F: Femenino, M: Masculino');
            $table->string('direccion', 500);
            $table->date('fecha_nacimiento');
            $table->integer('id_tipo_identificacion')->unsigned()->comment('cedula, pasaporte...por defecto será cedula y este campo se ocultará');
            $table->string('documento_identidad', 30)->unique()->comment('numero de cedula o pasaporte');
            $table->integer('anios_experiencia')->default(0)->comment('años de experiencia del instructor');
            $table->string('ocupacion',250)->comment('ocupacion u oficio del instructor');
            $table->string('especialidad')->nullable();
            $table->integer('calificacion')->unsigned()->default(0)->comment('la calificacion promedio del instructor medido del 1 al 100');
            $table->integer('id_nivel_academico')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructores');
    }
}
