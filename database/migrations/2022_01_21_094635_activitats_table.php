<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ActivitatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*ACTIVITATS es una prova*/
        Schema::create('activitats', function (Blueprint $table) {
            $table->id();
            $table->string('treballador');
            $table->datetime('inici_jornada')->nullable();
            $table->datetime('fi_jornada')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();
        });


        /*Schema::create('treballador', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('cognom');
            $table->string('identificador')->unique();
        });

        Schema::create('activitat', function (Blueprint $table) {
            $table->integer('id_treballador')->unsigned();
            $table->datetime('inici_jornada')->nullable();
            $table->datetime('fi_jornada')->nullable();
            $table->float('total')->nullable();
            $table->foreign('id_treballador')->references('id')->on('treballador');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activitats');
    }
}
