<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BoardGame;
use App\Models\Genre;

class GenreGamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = BoardGame::all();
        $genres = Genre::all();

        foreach ($games as $game) {
            $game->genres()->attach(
                $genres->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
