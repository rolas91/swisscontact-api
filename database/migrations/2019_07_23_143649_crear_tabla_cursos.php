<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_curso')->unsigned()->comment('nombre generico del curso, por ejemplo: Soldadura residencial I');
            $table->integer('id_modalidad')->unsigned()->comment('Accion Movil, Centro Fijo');
            $table->integer('id_modo')->unsigned()->comment('Complementacion, Especializacion, Habilitacion');
            $table->string('codigo',50)->comment('codigo unico para cada curso centro-curso-consecutivo del curso');
            $table->integer('id_pais')->unsigned();
            $table->integer('id_departamento')->unsigned();
            $table->integer('id_municipio')->unsigned();
            $table->string('direccion');
            $table->date('fecha_inicio')->comment('fecha que inicia el curso');
            $table->date('fecha_fin')->comment('fecha que finaliza el curso');
            $table->integer('id_estado')->unsigned()->comment('estado actual del curso');
            $table->decimal('costo')->comment('costo del curso por participante');
            $table->integer('cupos')->comment('cantidad de alumnos o cupos por curso');
            $table->date('fecha_fin_matricula')->nullable()->comment('dia que se cierra la matricula y ya no se podran registrar estudiantes al curso');
            $table->boolean('certificado')->comment('si el curso incluye o no un certificado');
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
        Schema::dropIfExists('cursos');
    }
}
