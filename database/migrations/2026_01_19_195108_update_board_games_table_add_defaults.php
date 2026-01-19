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
        Schema::table('board_games', function (Blueprint $table) {
            $table->integer('min_players')->default(1)->change();
            $table->integer('max_players')->default(2)->change();
            $table->integer('play_time_minutes')->default(0)->change();
            $table->integer('year_published')->default(0)->change();
            $table->string('publisher')->default('Unknown')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_games', function (Blueprint $table) {
            $table->integer('min_players')->change();
            $table->integer('max_players')->change();
            $table->integer('play_time_minutes')->change();
            $table->integer('year_published')->change();
            $table->string('publisher')->change();
        });
    }
};
