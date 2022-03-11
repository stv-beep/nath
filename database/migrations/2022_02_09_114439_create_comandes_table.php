<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treballador')->nullable();
            $table->foreign('treballador')->references('id')->on('users');
            $table->date('dia')->nullable();//jornada
            $table->unsignedBigInteger('tasca')->nullable();
            $table->float('total')->nullable();
            $table->foreign('tasca')->references('id')->on('tasques');
            $table->unsignedBigInteger('tipusTasca')->nullable();
            $table->foreign('tipusTasca')->references('id')->on('tasks_type');
            $table->string('geolocation')->nullable();
            $table->string('hostname')->nullable();
            $table->datetime('iniciTasca')->nullable();
            $table->datetime('fiTasca')->nullable();
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
        Schema::dropIfExists('comandes');
    }
}
