<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrearTablaRolesAccesos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_accesos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_acceso')->unsigned();
            $table->integer('id_rol')->unsigned();
            $table->boolean('ver')->default(0);
            $table->boolean('crear')->default(0);
            $table->boolean('editar')->default(0);
            $table->boolean('eliminar')->default(0);
            $table->timestamps();
        });

        Schema::table('roles_accesos', function (Blueprint $table) {
            $table->foreign('id_acceso')->references('id')->on('accesos')->onDelete('RESTRICT');
            $table->foreign('id_rol')->references('id')->on('roles')->onDelete('RESTRICT');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_accesos');
    }
}
