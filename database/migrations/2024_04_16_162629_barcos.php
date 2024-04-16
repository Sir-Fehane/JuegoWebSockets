<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('vidas');
            $table->integer('velocidad');
            $table->integer('posicionX');
            $table->integer('posicionY');
            $table->unsignedBigInteger('pantallaId');
            $table->foreign('pantallaId')->references('id')->on('pantallas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barcos');
    }
};
