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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player1_id');
            $table->foreign('player1_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('player2_id')->nullable();
            $table->foreign('player2_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('game_state')->default('waiting'); // Estado del juego
            $table->unsignedInteger('player1_score')->default(0); // Puntuación del jugador 1
            $table->unsignedInteger('player2_score')->default(0); // Puntuación del jugador 2
            $table->unsignedBigInteger('winner_id')->nullable(); // ID del jugador ganador
            // Agregamos más campos según sea necesario
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
