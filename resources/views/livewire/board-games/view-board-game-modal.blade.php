<div>
    <div x-data="{ open: @entangle('isOpen').live }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="open = false"
         @click.self="open = false"
         style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 transition-opacity"></div>
        
        <!-- Modal Container with Navigation Arrows -->
        <div class="relative z-50 mx-auto my-8 w-full max-w-6xl px-4 flex items-center justify-center gap-4" style="min-height: calc(100vh - 64px);">
            <!-- Left Arrow -->
            <button 
                wire:click="previousGame"
                {{ $currentIndex === 0 ? 'disabled' : '' }}
                class="flex-shrink-0 p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <!-- Modal -->
            <div class="w-full max-w-2xl">
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $boardGame?->title ?? '' }}</h3>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $currentIndex + 1 }} / {{ count($allGameIds) }}</span>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4">
                    @if($boardGame)
                        <div class="space-y-4">
                            @if($boardGame->image_path)
                                <div class="flex justify-center mb-4">
                                    <img src="{{ asset('storage/' . $boardGame->image_path) }}" 
                                         alt="{{ $boardGame->title }}" 
                                         class="max-w-md h-auto rounded-lg shadow-md">
                                </div>
                            @endif

                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.description') }}</h3>
                                <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->description ?? '-' }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.age_recommendation') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->age_recommendation ?? '-' }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.rating') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->rating ?? '-' }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.min_players') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->min_players ?? '-' }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.max_players') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->max_players ?? '-' }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.play_time_minutes') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->play_time_minutes ? $boardGame->play_time_minutes . ' ' . __('boardgames.attributes.minutes') : '-' }}</p>
                                </div>

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.year_published') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->year_published ?? '-' }}</p>
                                </div>

                                <div class="col-span-2">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.publisher') }}</h3>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $boardGame->publisher ?? '-' }}</p>
                                </div>

                                <div class="col-span-2">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('boardgames.attributes.genres') }}</h3>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @if($boardGame->genres && $boardGame->genres->count() > 0)
                                            @foreach($boardGame->genres as $genre)
                                                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                                    {{ $genre->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <p class="text-gray-900 dark:text-gray-100">-</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-6">
                                <button 
                                    wire:click="previousGame"
                                    @click="previousGame"
                                    {{ $currentIndex === 0 ? 'disabled' : '' }}
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    {{ __('previous') }}
                                </button>
                                <button @click="open = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                                    {{ __('boardgames.form.close') }}
                                </button>
                                <button 
                                    wire:click="nextGame"
                                    {{ $currentIndex === count($allGameIds) - 1 ? 'disabled' : '' }}
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                    {{ __('next') }}
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Right Arrow -->
            <button 
                wire:click="nextGame"
                {{ $currentIndex === count($allGameIds) - 1 ? 'disabled' : '' }}
                class="flex-shrink-0 p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 disabled:opacity-30 disabled:cursor-not-allowed transition-colors">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</div>
