<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaFormularios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formularios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tipo')->unsigned()->comment('tipo del formulario. Por ejemplo: Encuesta, Examen, Formulario');
            $table->integer('id_tema')->unsigned()->nullable()->comment('Tema del formulario. Por sejemplo: azul,verde,rojo, etc..');
            $table->string('nombre', 191)->comment('nombre del fomulario');
            $table->string('url', 191)->unique()->comment('url o slug personalizado del formulario, por defecto se generará un string random a manera de token de 8 caracteres');
            $table->date('fecha_inicio')->comment('fecha de inicio en que la encuesta estará disponible para llenarla');
            $table->date('fecha_fin')->comment('ultima fecha en que la encuesta estará disponible para llenarla');
            $table->decimal('duracion')->nullable()->comment('Duracion en minutos que el usuario dispone para contestar el formulario ,0 para duracion indefinida');
            $table->decimal('nota_maxima')->default(0)->comment('valor de la nota maxima que el formulario/examen permite obtener, esto sirve para calcular un porcentaje de cumplimiento o nota');
            $table->boolean('ordenar_aleatoriamente')->default(false)->comment('Este campo sirve para indicar que las preguntas aparezcan en un orden aleatorio al momento de presentar el formulario, muy útil para examenes');
            $table->integer('id_modo')->default(5603)->unsigned()->comment('Vinculado,Abierto,Anonimo: Permite seleccionar el modo del formulario y definir el nivel de animato para llenar el formulario');
            $table->integer('id_usuario_creacion')->unsigned()->comment('usuario que crea el formulario');
            $table->integer('id_usuario_modificacion')->unsigned()->nullable()->comment('usuario que modifica el formulario');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('formularios', function (Blueprint $table) {
            $table->foreign('id_tipo')->references('id')->on('catalogos_detalles')->onDelete('RESTRICT');
            $table->foreign('id_modo')->references('id')->on('catalogos_detalles')->onDelete('RESTRICT');
            $table->foreign('id_tema')->references('id')->on('catalogos_detalles')->onDelete('RESTRICT');
            $table->foreign('id_usuario_creacion')->references('id')->on('usuarios')->onDelete('RESTRICT');
            $table->foreign('id_usuario_modificacion')->references('id')->on('usuarios')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formularios', function (Blueprint $table) {
            $table->dropForeign('id_tipo');
            $table->dropForeign('id_modo');
            $table->dropForeign('id_usuario');
            $table->dropForeign('id_centro');
            $table->dropForeign('id_curso');
            $table->dropForeign('id_tema');
            $table->dropForeign('id_usuario_creacion');
            $table->dropForeign('id_usuario_modificacion');
        });
        Schema::dropIfExists('formularios');
    }
}
