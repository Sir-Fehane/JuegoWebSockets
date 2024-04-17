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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player1_id');
            $table->unsignedBigInteger('player2_id');
            $table->integer('player1_score')->default(0);
            $table->integer('player2_score')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'finished']);
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->timestamps();

            $table->foreign('player1_id')->references('id')->on('users');
            $table->foreign('player2_id')->references('id')->on('users');
            $table->foreign('winner_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
};
