<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasques', function (Blueprint $table) {
            $table->id();
            $table->string('tasca');
            $table->unsignedBigInteger('tipusTasca')->nullable();
            $table->foreign('tipusTasca')->references('id')->on('tasks_type');
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
        Schema::dropIfExists('tasques');
    }
}
