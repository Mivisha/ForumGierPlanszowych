<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);
    
        $userId = auth()->id();
        $body = trim($validated['body']);
    
        $lock = Cache::lock("comment-submit:{$userId}:{$post->id}", 5);
    
        if (! $lock->get()) {
            return redirect()->route('dashboard')->with('warning', 'Poczekaj, komentarz jest już wysyłany.');
        }
    
        try {
            $isDuplicate = Comment::query()
                ->where('user_id', $userId)
                ->where('post_id', $post->id)
                ->where('body', $body)
                ->where('created_at', '>=', now()->subSeconds(10))
                ->exists();
    
            if ($isDuplicate) {
                return redirect()->route('dashboard')->with('warning', 'Taki komentarz już istnieje.');
            }
    
            Comment::create([
                'user_id' => $userId,
                'post_id' => $post->id,
                'body' => $body,
            ]);
    
            return redirect()->route('dashboard')->with('success', 'Komentarz został dodany pomyślnie.');
        } finally {
            $lock->release();
        }
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->route('dashboard')->with('success', 'Komentarz został pomyślnie usunięty.');
    }
}