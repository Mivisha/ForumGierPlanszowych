<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_board_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('board_game_id')->constrained('board_games')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'board_game_id']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('favorite_board_games');
    }
};
