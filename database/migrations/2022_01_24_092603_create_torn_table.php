<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTornTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torns', function (Blueprint $table) {
            $table->id();
            $table->date('jornada');
            $table->unsignedBigInteger('treballador');
            $table->foreign('treballador')->references('id')->on('users');
            $table->float('total')->nullable();
            $table->string('geolocation')->nullable();
            $table->string('hostname')->nullable();
            $table->datetime('iniciTorn')->nullable();
            $table->datetime('fiTorn')->nullable();
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
        Schema::dropIfExists('torns');
    }
}
