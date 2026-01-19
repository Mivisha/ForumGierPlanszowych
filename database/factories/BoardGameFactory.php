<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoardGame>
 */
class BoardGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=> fake()->sentence(3),
            'description'=> fake()->paragraph(),
            'min_players'=> fake()->numberBetween(1,4),
            'max_players'=> fake()->numberBetween(4,10),
            'play_time_minutes' => fake()->numberBetween(15,180),
            'age_recommendation'=> fake()->numberBetween(3,18),
            'year_published'=> fake()->numberBetween(2000,2025),
            'publisher'=> fake()->company(),
            'rating'=> fake()->randomFloat(2,0,10),
    
        ];
    }
}
