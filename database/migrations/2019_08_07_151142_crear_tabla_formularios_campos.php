<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaFormulariosCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formularios_campos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_formulario')->unsigned()->comment('id del formulario padre');
            $table->integer('id_seccion')->unsigned()->comment('id del formulario padre');
            $table->integer('id_tipo')->unsigned()->comment('id del tipo del campo');
            $table->string('nombre',50)->nullable()->comment('nombre del campo que se verá en los reportes');
            $table->string('texto',4000)->comment('Pregunta o texto del campo');
            $table->string('subtitulo',4000)->nullable()->comment('este campo sirve para brindar alguna informacion extra relacionada con el campo');
            $table->string('imagen',512)->nullable()->comment('imagen');
            $table->boolean('requerido')->default(0)->comment('este campo sirve para definir si el campo es requerida');
            $table->string('respuesta_correcta')->nullable()->comment('Si el formulario es de tipo examen, guardamos el tipo de respuesta correcta');
            $table->string('minimo',100)->nullable()->comment('este campo sirve para definir a los tipo input:number y las fechas definir un valor minimo');
            $table->string('maximo',100)->nullable()->comment('este campo sirve para definir a los tipo input:number y las fechas definir un valor maximo');
            $table->text('tipo_input')->nullable()->comment('JSON este campo sirve para definir la estructura interna del campo como icono y id, etc');
            $table->text('opciones')->nullable()->comment('JSON esta opcion sirve para almacenar las opciones del campo');
            $table->decimal('nota',18,2)->default(0)->comment('nota, puntos, valor o peso que tiene esa pregunta dentro del formulario');
            $table->boolean('editando')->default(false)->comment('este es un campo auxiliar que ayuda al momento de construir el formulario');
            $table->string('temp',4000)->nullable()->comment('este es un campo auxiliar que ayuda al momento de construir el formulario');
            $table->text('respuesta')->nullable()->comment('este campo se utiliza como campo auxiliar para guardar la respuesta');
            $table->text('arregloTable')->nullable()->comment('este campo sirve para guardar el arreglo de notas');
        });

        Schema::table('formularios_campos', function (Blueprint $table) {
            $table->foreign('id_formulario')->references('id')->on('formularios')->onDelete('RESTRICT');
            $table->foreign('id_seccion')->references('id')->on('formularios_secciones')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formularios_secciones', function (Blueprint $table) {
            $table->dropForeign('id_formulario');
        });

        Schema::table('formularios_campos', function (Blueprint $table) {
            $table->dropForeign('id_seccion');
        });

        Schema::dropIfExists('formularios_campos');
    }
}
