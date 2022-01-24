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
        Schema::create('activitat', function (Blueprint $table) {
            $table->id();
            $table->integer('id_treballador')->unsigned();
            $table->datetime('inici_jornada')->nullable();
            $table->datetime('fi_jornada')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();
            $table->foreign('id_treballador')->references('id')->on('treballador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activitat');
    }
}
