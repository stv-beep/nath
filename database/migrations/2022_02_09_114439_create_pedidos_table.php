<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treballador')->nullable();
            $table->foreign('treballador')->references('id')->on('users');
            $table->date('dia')->nullable();//jornada
            $table->unsignedBigInteger('tasca')->nullable();
            $table->float('total')->nullable();
            $table->foreign('tasca')->references('id')->on('tasques');
            $table->datetime('iniciTasca')->nullable();
            $table->datetime('fiTasca')->nullable();
            $table->unsignedBigInteger('jornada')->nullable();
            $table->foreign('jornada')->references('id')->on('jornades');
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
        Schema::dropIfExists('pedidos');
    }
}
