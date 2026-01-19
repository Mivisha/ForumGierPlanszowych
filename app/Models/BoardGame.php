<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardGame extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'player_count_min',
        'player_count_max',
        'playtime_min',
        'playtime_max',
        'age_recommendation',
        'min_players',
        'max_players',
        'play_time_minutes',
        'year_published',
        'publisher',
        'image_path',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'age_recommendation' => 'integer',
        ];
    }

    public function getAgeRecommendationAttribute($value)
    {
        if ($value === null) {
            return null;
        }
        // Remove existing + if present, then add it back
        $value = str_replace('+', '', $value);
        return "{$value}+";
    }
    
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'board_games_genres')
            ->withTrashed()
            ->orderBy('name');
    }
}
