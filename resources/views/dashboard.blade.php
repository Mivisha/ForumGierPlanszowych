<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Top Rated Board Games Section -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 bg-white dark:bg-neutral-900">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">{{ __('Ulubience użytkowników') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse(\App\Models\BoardGame::where('rating', '>', 0)->orderBy('rating', 'desc')->limit(4)->get() as $game)
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden hover:shadow-lg dark:hover:shadow-neutral-950/50 transition-shadow duration-300">
                        <div class="relative w-full h-48 bg-gray-200 dark:bg-gray-800 overflow-hidden">
                            @if($game->image_path)
                                <img src="{{ asset('storage/' . $game->image_path) }}" alt="{{ $game->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ $game->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $game->description }}</p>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $game->rating }}</span>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $game->age_recommendation }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($game->genres->take(2) as $genre)
                                    <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:text-blue-200">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-4 text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">{{ __('Niema ocenionych gier') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Секція постів форуму -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900">
            <div class="p-6 border-b border-neutral-200 dark:border-neutral-700 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Posty forum</h2>
                @auth
                    <a href="{{ route('posts.create') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 font-semibold shadow-sm transition hover:bg-blue-500 dark:bg-white dark:text-gray-800 dark:hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5" />
                        </svg>
                        <span>{{ __('posts.actions.create') }}</span>
                    </a>
                @endauth
            </div>
        
            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse(\App\Models\Post::with(['user', 'boardGames', 'genres'])->latest()->get() as $post)                    
                    @php
                        $displayTitle = $post->title
                            ?: (preg_split('/(?<=[.!?])\s+/', trim($post->body), 2)[0] ?? $post->body);
                        $bodyPreview = mb_strlen($post->body) > 100
                            ? mb_substr($post->body, 0, 100) . '...'
                            : $post->body;
                    @endphp
                    <div class="p-6" x-data="{ commentsOpen: false }">
                        <!-- Вгорі поста -->
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $post->user->name }}</p>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2">{{ $displayTitle }}</h3>
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @foreach($post->boardGames as $game)
                                <span class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:text-blue-200">
                                    {{ $game->title }}
                                </span>
                            @endforeach
                            @foreach($post->genres as $genre)
                                <span class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:text-purple-200">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mb-4 inline-block">
                            {{ $bodyPreview }}
                        </a>        
                        <livewire:posts.comment-section :post="$post" :key="'post-comments-'.$post->id" />
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-gray-500 dark:text-gray-400">Na razie niema żadnego posta.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Placeholder -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
    </div>
</x-layouts.app>