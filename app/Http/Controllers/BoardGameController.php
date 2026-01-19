<?php

namespace App\Http\Controllers;

use App\Models\BoardGame;
use App\Models\Genre;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BoardGameController extends Controller
{
    use AuthorizesRequests;

    public function create(): View
    {
        $this->authorize('create', BoardGame::class);
        $genres = Genre::whereNull('deleted_at')->get();
        return view('board-games.create', compact('genres'));
    }

    public function store(): RedirectResponse
    {
        $this->authorize('create', BoardGame::class);
        
        $validated = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'age_recommendation' => 'nullable|integer|min:0|max:100',
            'min_players' => 'nullable|integer|min:1',
            'max_players' => 'nullable|integer|min:1',
            'play_time_minutes' => 'nullable|integer|min:0',
            'year_published' => 'nullable|integer|min:1900|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:5120',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        // Set defaults for empty values
        $validated['min_players'] = $validated['min_players'] ?? 1;
        $validated['max_players'] = $validated['max_players'] ?? 2;
        $validated['play_time_minutes'] = $validated['play_time_minutes'] ?? 0;
        $validated['year_published'] = $validated['year_published'] ?? 0;
        $validated['publisher'] = $validated['publisher'] ?? 'Unknown';
        $validated['age_recommendation'] = $validated['age_recommendation'] ?? 0;

        // Handle image upload
        if (request()->hasFile('image')) {
            $path = request()->file('image')->store('board-games', 'public');
            $validated['image_path'] = $path;
        }

        $boardGame = BoardGame::create($validated);
        
        if (request('genres')) {
            $boardGame->genres()->sync(request('genres'));
        }

        return redirect(route('board-games.index'))
            ->with('flash', ['message' => __('boardgames.messages.created'), 'type' => 'success']);
    }

    public function edit(BoardGame $boardGame): View
    {
        $this->authorize('update', $boardGame);
        $genres = Genre::whereNull('deleted_at')->get();
        return view('board-games.edit', compact('boardGame', 'genres'));
    }

    public function update(BoardGame $boardGame): RedirectResponse
    {
        $this->authorize('update', $boardGame);
        
        $validated = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'age_recommendation' => 'nullable|integer|min:0|max:100',
            'min_players' => 'nullable|integer|min:1',
            'max_players' => 'nullable|integer|min:1',
            'play_time_minutes' => 'nullable|integer|min:0',
            'year_published' => 'nullable|integer|min:1900|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp,tiff|max:5120',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        // Set defaults for empty values
        $validated['min_players'] = $validated['min_players'] ?? 1;
        $validated['max_players'] = $validated['max_players'] ?? 2;
        $validated['play_time_minutes'] = $validated['play_time_minutes'] ?? 0;
        $validated['year_published'] = $validated['year_published'] ?? 0;
        $validated['publisher'] = $validated['publisher'] ?? 'Unknown';
        $validated['age_recommendation'] = $validated['age_recommendation'] ?? 0;

        // Handle image upload
        if (request()->hasFile('image')) {
            // Delete old image if exists
            if ($boardGame->image_path && \Storage::disk('public')->exists($boardGame->image_path)) {
                \Storage::disk('public')->delete($boardGame->image_path);
            }
            $path = request()->file('image')->store('board-games', 'public');
            $validated['image_path'] = $path;
        }

        $boardGame->update($validated);
        
        if (request('genres')) {
            $boardGame->genres()->sync(request('genres'));
        }

        return redirect(route('board-games.index'))
            ->with('flash', ['message' => __('boardgames.messages.updated'), 'type' => 'warning']);
    }

    public function destroy(BoardGame $boardGame): RedirectResponse
    {
        $this->authorize('delete', $boardGame);
        
        // Delete image if exists
        if ($boardGame->image_path && \Storage::disk('public')->exists($boardGame->image_path)) {
            \Storage::disk('public')->delete($boardGame->image_path);
        }
        
        $boardGame->delete();
        
        return redirect(route('board-games.index'))
            ->with('flash', ['message' => __('boardgames.messages.deleted'), 'type' => 'danger']);
    }
}
