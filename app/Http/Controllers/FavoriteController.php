<?php

namespace App\Http\Controllers;

use App\Models\BoardGame;
use Illuminate\Http\RedirectResponse;

class FavoriteController extends Controller
{
    public function toggle(BoardGame $boardGame): RedirectResponse
    {
        $user = auth()->user();
        
        if ($user->favoriteGames()->where('board_game_id', $boardGame->id)->exists()) {
            $user->favoriteGames()->detach($boardGame->id);
        } else {
            $user->favoriteGames()->attach($boardGame->id);
        }
        
        return back()->with('success', 'Улюблені оновлені.');
    }

    public function index()
    {
        $favorites = auth()->user()->favoriteGames()->paginate(12);
        
        return view('favorites.index', compact('favorites'));
    }
}