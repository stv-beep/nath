<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJornadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jornades', function (Blueprint $table) {
            $table->id();
            $table->date('dia')->nullable();//jornada
            $table->unsignedBigInteger('treballador')->nullable();
            $table->foreign('treballador')->references('id')->on('users');
            $table->float('total')->nullable();
            $table->unsignedBigInteger('id_activitat')->nullable();
            $table->foreign('id_activitat')->references('id')->on('activitats');
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
        Schema::dropIfExists('jornades');
    }
}
