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
            $table->float('total_cron')->nullable();
            $table->string('inici_jornada')->nullable();//inici de la jornada
            //$table->datetime('fi_mig_jornada')->nullable();//final primera meitat de jornada partida
            //$table->datetime('inici_mig_jornada')->nullable();//inici de la segona meitat
            $table->string('fi_jornada')->nullable();//final de la jornada
            $table->float('total')->nullable();
            $table->timestamps();
        });
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
