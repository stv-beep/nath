<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activitats', function (Blueprint $table) {
            $table->id();
            $table->date('jornada');
            $table->unsignedBigInteger('treballador');
            $table->foreign('treballador')->references('id')->on('users');
            $table->float('total')->nullable();
            $table->datetime('iniciJornada')->nullable();
            $table->datetime('fiJornada')->nullable();
            //$table->float('totalCron')->nullable();
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
