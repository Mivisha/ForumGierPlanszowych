<div>
    <button
        type="button"
        wire:click="toggle"
        class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
        <span>Komentarze ({{ $this->comments->count() }})</span>
    </button>

    @if($open)
        <div class="mt-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
            @if($this->comments->count() > 0)
                <div class="space-y-3 mb-4 {{ $openByDefault ? '' : 'max-h-60 overflow-y-auto' }}">
                    @foreach($this->comments as $comment)
                        <div class="bg-white dark:bg-gray-700 p-3 rounded">
                            <div class="flex items-center justify-between mb-1">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                                @auth
                                    @if(auth()->id() === $comment->user_id)
                                        <button
                                            type="button"
                                            wire:click="deleteComment({{ $comment->id }})"
                                            class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                        >
                                            Skasuj
                                        </button>
                                    @endif
                                @endauth
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $comment->body }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Brak komentarzy.</p>
            @endif

            @auth
                <form wire:submit.prevent="addComment" class="space-y-2">
                    <textarea
                        wire:model.defer="body"
                        rows="2"
                        placeholder="Dodaj komentarz..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                    @error('body')
                        <p class="text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="addComment"
                        class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-500 disabled:opacity-60 disabled:cursor-not-allowed dark:bg-white dark:text-gray-800 dark:hover:bg-gray-100"
                    >
                        <span wire:loading.remove wire:target="addComment">Wyślij</span>
                        <span wire:loading wire:target="addComment">Wysyłanie...</span>
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Zaloguj się</a>, aby dodać komentarz.
                </p>
            @endauth
        </div>
    @endif
</div>