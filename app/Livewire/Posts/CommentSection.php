<?php

namespace App\Livewire\Posts;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CommentSection extends Component
{
    public Post $post;
    public bool $open = false;
    public bool $openByDefault = false;

    #[Validate('required|string|max:1000')]
    public string $body = '';

    public function mount(): void
    {
        $this->open = $this->openByDefault;
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function addComment(): void
    {
        if (!Auth::check()) {
            return;
        }

        $validated = $this->validate();
        $text = trim($validated['body']);

        if ($text === '') {
            return;
        }

        $isDuplicate = Comment::query()
            ->where('user_id', Auth::id())
            ->where('post_id', $this->post->id)
            ->where('body', $text)
            ->where('created_at', '>=', now()->subSeconds(10))
            ->exists();

        if ($isDuplicate) {
            $this->addError('body', 'Схожий коментар уже щойно додано.');
            return;
        }

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
            'body' => $text,
        ]);

        $this->reset('body');
    }

    public function deleteComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        if (!Auth::check() || Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();
    }

    public function getCommentsProperty()
    {
        return $this->post->comments()->with('user')->oldest()->get();
    }

    public function render()
    {
        return view('livewire.posts.comment-section');
    }
}