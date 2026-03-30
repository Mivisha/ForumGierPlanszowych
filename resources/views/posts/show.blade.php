<x-layouts.app :title="$post->title ?? 'Post'">
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('dashboard') }}" class="mb-6 inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Wstecz
        </a>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
            <!-- Шапка поста -->
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $post->user->name }}</p>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                @php
                    $displayTitle = $post->title
                        ?: (preg_split('/(?<=[.!?])\s+/', trim($post->body), 2)[0] ?? $post->body);
                @endphp
                {{ $displayTitle }}
            </h1>

            <!-- Теги -->
            @if($post->boardGames->count() > 0 || $post->genres->count() > 0)
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach($post->boardGames as $game)
                        <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-3 py-1 text-sm font-medium text-blue-800 dark:text-blue-200">
                            {{ $game->title }}
                        </span>
                    @endforeach
                    @foreach($post->genres as $genre)
                        <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-3 py-1 text-sm font-medium text-purple-800 dark:text-purple-200">
                            {{ $genre->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Дата створення -->
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">{{ $post->created_at->format('d.m.Y H:i') }}</p>

            <!-- Текст поста -->
            <div class="prose prose-sm dark:prose-invert max-w-none mb-8">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $post->body }}</p>
            </div>

            <!-- Коментарі -->
            <div class="border-t border-neutral-200 dark:border-neutral-700 pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Коментарі ({{ $post->comments->count() }})
                </h2>

            @livewire('posts.comment-section', ['post' => $post, 'openByDefault' => true], key('show-comments-'.$post->id))            </div>
        </div>
    </div>
</x-layouts.app>