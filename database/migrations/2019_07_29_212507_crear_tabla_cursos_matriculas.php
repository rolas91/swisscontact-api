<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCursosMatriculas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos_matriculas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_curso')->unsigned();
            $table->integer('id_participante')->unsigned();
            $table->string('nombres_participante', 50);
            $table->string('apellidos_participante', 50);
            $table->string('correo', 128)->nullable()->comment('correo electrónico del participante');
            $table->string('telefono', 20)->nullable()->comment('numero de telefono o celular del participante');
            $table->integer('id_tipo_identificacion')->unsigned()->comment('cedula, pasaporte...por defecto será cedula y este campo se ocultará');
            $table->string('documento_identidad', 30)->nullable()->comment('numero de cedula o pasaporte');
            $table->integer('edad')->comment('edad del participante al momento de cursar el curso o taller');
            $table->integer('id_estado_civil')->unsigned()->comment('soltero, casado, union libre, etc.. al momemto que se inscribe en el curso');
            $table->char('sexo')->comment('F: Femenino, M: Masculino');
            $table->integer('id_pais')->unsigned()->comment('pais de residencia');
            $table->integer('id_departamento')->unsigned()->comment('departamento de residencia');
            $table->integer('id_municipio')->unsigned()->comment('ciudad de residencia');
            $table->string('direccion', 255)->comment('direccion actual del participante');
            //Informacion academica
            $table->integer('id_nivel_academico')->unsigned()->comment('Primaria, Secundaria, Universidad, Tenico');
            $table->boolean('estudiando')->comment('El participante actualmente estudia?');
            $table->string('curso_estudiando')->nullable()->comment('describe los cursos o carrera que el participante lleva actualmente');
            $table->boolean('trabajando')->comment('El participante actualmente se encuentra trabajando');
            $table->string('lugar_trabajo')->nullable()->comment('si se encuentra trabajando aqui poner el nombre de la empresa donde trabaja el participante');
            $table->decimal('salario', 18, 2)->default(0)->comment('aqui poner cuanto gana el participante en cordobas al mes');
            //Datos del referencia
            $table->string('referencia_nombre')->nullable()->comment('Nombre del referencia del participante');
            $table->integer('id_parentezco')->unsigned()->nullable()->comment('Parentezco del referencia en caso de ingresarlo');
            $table->string('referencia_cedula')->nullable()->comment('Cedula del referencia del participante');
            $table->string('referencia_telefono')->nullable()->comment('Telefono del referencia del participante');
            $table->string('referencia_correo')->nullable()->comment('Correo electronico del referencia del participante');
            //Calificacion
            $table->integer('calificacion')->default(0);
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
       Schema::dropIfExists('cursos_matriculas');
    }
}
