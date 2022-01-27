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
            $table->float('total_cron')->nullable();
            $table->datetime('inici_jornada')->nullable();
            $table->datetime('fi_jornada')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('id_treballador');
            $table->foreign('id_treballador')->references('id')->on('users');
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
