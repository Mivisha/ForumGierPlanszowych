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
        
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('title')->nullable();
                $table->longText('body');
                $table->timestamps();
             });
             Schema::create('board_game_post', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
                $table->foreignId('board_game_id')->constrained('board_games')->cascadeOnDelete();
                $table->unique(['post_id', 'board_game_id']);
            });
    
            Schema::create('genre_post', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
                $table->foreignId('genre_id')->constrained('genres')->cascadeOnDelete();
                $table->unique(['post_id', 'genre_id']);
            });
        
               
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {   
        Schema::dropIfExists('genre_post');
        Schema::dropIfExists('board_game_post');
        Schema::dropIfExists('posts');
    }
};
