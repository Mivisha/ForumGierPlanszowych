<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_games_genres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('genre_id');
            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
                ->onDelete('no action');
            $table->unsignedBigInteger('board_game_id');
            $table->foreign('board_game_id')
                ->references('id')
                ->on('board_games')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_games_genres');
    }
};
