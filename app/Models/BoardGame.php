<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'player_count_min',
        'player_count_max',
        'playtime_min',
        'playtime_max',
        'age_recommendation',
    ];
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
