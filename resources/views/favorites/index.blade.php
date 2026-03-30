<x-layouts.app :title="__('Ulubione gry')">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 mb-4">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Wstecz
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ulubione gry</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Razem: {{ $favorites->total() }}</p>
        </div>

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($favorites as $game)
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
                            <form method="POST" action="{{ route('favorites.toggle', $game) }}" style="position: absolute; top: 8px; right: 8px;">
                                @csrf
                                <button type="submit" class="p-2 bg-white dark:bg-gray-900 rounded-full shadow-lg hover:scale-110 transition-transform">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
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
                @endforeach
            </div>

            <!-- Пагінація -->
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @else
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-12 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nie znaleziono ulubionych gier</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Zacznij dodawać gry w ulubionych.</p>
                <a href="{{ route('board-games.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                    Zobaczyć gry
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>