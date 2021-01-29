<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaCentros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_tipo')->unsigned()->default()->comment('El tipo de centro Técnico Servicios Capacitación');
            $table->string('nombre')->comment('El nombre del centro');
            $table->integer('id_pais')->unsigned()->comment('el pais del centro, por defecto Nicaragua');
            $table->integer('id_departamento')->unsigned()->comment('departamento donde esta ubicado el centro');
            $table->integer('id_municipio')->comment('municipio donde esta ubicado el centro')->unsigned();
            $table->string('lema')->nullable()->comment('lema del centro');
            $table->string('logo',500)->nullable()->comment('url al logo');
            $table->string('banner',500)->nullable()->comment('url al banner');
            $table->string('descripcion',2000)->nullable()->comment('descripcion del centro');
            $table->string('quienes_somos',2000)->nullable()->comment('una breve presentacion del centro');
            $table->string('mision',2000)->nullable()->comment('descripcion del centro');
            $table->string('vision',2000)->nullable()->comment('descripcion del centro');
            $table->string('valores',2000)->nullable()->comment('valores del centro');
            $table->string('direccion',2000)->comment('direccion del centro');
            $table->string('latitud')->nullable()->comment('latitud de la empresa en el mapa');
            $table->string('longitud')->nullable()->comment('latitud de la empresa en el mapa');
            $table->string('contacto_nombre')->comment('nombre de contacto de la persona encargada del centro');
            $table->string('contacto_telefono')->nullable()->comment('telefono de contacto de la person encargada');
            $table->string('contacto_correo')->nullable()->comment('correo de contacto de la persona encargada del centro');
            $table->string('telefono')->nullable()->comment('telefono del centro para acceso al publico');
            $table->string('correo')->nullable()->comment('correo el centro para contacto al publico');
            $table->string('web_url')->nullable()->comment('url de la pagina web del centro para acceso al publico');
            $table->string('facebook')->nullable()->comment('link del facebook del centro para contacto al publico');
            $table->string('instagram')->nullable()->comment('link del instagram del centro para contacto al publico');
            $table->string('twitter')->nullable()->comment('link del twitter del centro para contacto al publico');
            $table->string('youtube')->nullable()->comment('link del youtube del centro para contacto al publico');
            $table->integer('computadoras')->default(0)->comment('Cantidad de computadoras en buen estado en el centro disponibles para la enseñanza');
            $table->integer('tablets')->default(0)->comment('Cantidad de tablets en buen estado en el centro disponibles para la enseñanza');
            $table->integer('celulares')->default(0)->comment('Cantidad de celulares en buen estado en el centro disponibles para la enseñanza');
            $table->integer('velocidad_internet')->default(0)->comment('Velocidad del internet del centro ej: 5 Mbps');
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
        Schema::dropIfExists('centros');
    }
}
