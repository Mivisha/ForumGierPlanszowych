<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Genre::factory()->create([
            'name' => 'Strategiczna',
        ]);
        Genre::factory()->create([
            'name' => 'Ekonomiczna',
        ]);
        Genre::factory()->create([
            'name' => 'Przygodowa',
        ]);
        Genre::factory()->create([
            'name' => 'Fabularna (RPG)',
        ]);
        Genre::factory()->create([
            'name' => 'Kooperacyjna',
        ]);

    }
}
