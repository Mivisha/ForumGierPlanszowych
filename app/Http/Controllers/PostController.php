<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Genre;
use App\Models\BoardGame;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    use AuthorizesRequests;
  
    public function create(): View
    {
        $boardGames = BoardGame::orderBy('title')->get();
        $genres = Genre::orderBy('name')->get();
        $submissionToken = (string) Str::uuid();

        session()->put('post_submission_token', $submissionToken);

        return view('posts.create', compact('boardGames', 'genres', 'submissionToken'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'submission_token' => ['required', 'string'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'board_games' => ['nullable', 'array'],
            'board_games.*' => ['exists:board_games,id'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
        ]);

        $sessionToken = (string) $request->session()->pull('post_submission_token');

        if ($sessionToken === '' || ! hash_equals($sessionToken, $validated['submission_token'])) {
            throw ValidationException::withMessages([
                'body' => 'Цю форму вже було відправлено. Онови сторінку та спробуй ще раз.',
            ]);
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'] ?? null,
            'body' => $validated['body'],
        ]);

        $post->boardGames()->sync($validated['board_games'] ?? []);
        $post->genres()->sync($validated['genres'] ?? []);

        return redirect()->route('dashboard')->with('success', 'Post created successfully.');
    }
    public function show(Post $post): View
    {
        return view('posts.show', [
            'post' => $post->load(['user', 'boardGames', 'genres']),
        ]);
    }
}
