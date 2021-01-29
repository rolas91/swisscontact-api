<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaParticipantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('foto',250)->comment('url de la foto del participante')->nullable();
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('correo', 128)->nullable()->comment('correo electrónico del participante');
            $table->string('telefono', 20)->nullable()->comment('telefono de contacto del participante, preferiblemente celular');
            $table->integer('id_tipo_identificacion')->unsigned()->comment('cedula, pasaporte...por defecto será cedula y este campo se ocultará');
            $table->string('documento_identidad', 30)->nullable()->comment('numero de cedula o pasaporte');
            $table->boolean('menor_edad')->default(false)->comment('una bandera que indica si el participante es menor de edad');
            $table->date('fecha_nacimiento');
            $table->integer('id_estado_civil')->unsigned()->comment('soltero, casado, union libre, etc');
            $table->char('sexo')->comment('F: Femenino, M: Masculino');
            $table->integer('id_pais')->unsigned()->comment('pais de residencia del participante');
            $table->integer('id_departamento')->unsigned()->comment('departamento de residencia del participante');
            $table->integer('id_ciudad')->unsigned()->comment('ciudad de residencia del participante');
            $table->string('direccion', 255);
            $table->integer('id_nivel_educacion')->unsigned()->comment('Primaria, Secundaria, Universidad, Tenico');
            $table->boolean('estudiando')->default(false)->comment('El participante actualmente estudia?');
            $table->string('curso_estudiando')->nullable()->comment('describe los cursos o carrera que el participante lleva actualmente');
            $table->boolean('trabajando')->default(false)->comment('El participante actualmente se encuentra trabajando');
            $table->string('lugar_trabajo')->nullable()->comment('si se encuentra trabajando aqui poner el nombre de la empresa donde trabaja el participante');
            $table->decimal('salario', 18, 2)->default(0)->comment('aqui poner cuanto gana el participante en cordobas al mes');
            $table->string('referencia_nombre')->nullable()->comment('Nombre del referencia del participante');
            $table->integer('id_parentezco')->unsigned()->nullable()->comment('Parentezco del referencia en caso de ingresarlo');
            $table->string('referencia_cedula')->nullable()->comment('Cedula del referencia del participante');
            $table->string('referencia_telefono')->nullable()->comment('Telefono del referencia del participante');
            $table->string('referencia_correo')->nullable()->comment('Correo electronico del referencia del participante');
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
        Schema::dropIfExists('participantes');
    }
}
