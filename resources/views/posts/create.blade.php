<x-layouts.app :title="__('Create Post')">
    @php
        $selectedBoardGames = old('board_games', []);
        $selectedGenres = old('genres', []);
    @endphp

    <section class="w-full max-w-2xl mx-auto">
        <form method="POST" action="{{ route('posts.store') }}" class="space-y-6"    onsubmit="const submitButton = this.querySelector('[type=submit]'); if (submitButton) { submitButton.disabled = true; submitButton.setAttribute('aria-disabled', 'true'); }">
            @csrf
            <input type="hidden" name="submission_token" value="{{ $submissionToken }}">


            <div class="mb-6">
                <flux:heading>Nowy post</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    Napisz post na forum i opcjonalnie powiąż go z konkretną grą planszową albo gatunkiem.
                </p>
            </div>

            <flux:field>
                <flux:label>Tytuł</flux:label>
                <flux:input
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Krótki tytuł posta"
                />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Treść posta</flux:label>
                <flux:textarea
                    name="body"
                    rows="8"
                    placeholder="Napisz, o czym chcesz porozmawiać..."
                >{{ old('body') }}</flux:textarea>
                <flux:error name="body" />
            </flux:field>

            <flux:field>
                <div class="genre-picker__intro">
                    <flux:label>Powiązane gry planszowe</flux:label>
                    <p class="genre-picker__hint">Wybierz gry, których dotyczy ten post.</p>
                </div>
            
                <div class="genre-flow" data-genre-select>
                    <div class="genre-dropdown">
                        <button type="button" class="genre-dropdown__trigger" data-genre-toggle>
                            Wybierz gry planszowe
                            <span class="genre-dropdown__chevron" aria-hidden="true"></span>
                        </button>
            
                        <div class="genre-dropdown__panel" data-genre-panel hidden>
                            <ul class="genre-dropdown__list">
                                @foreach($boardGames as $boardGame)
                                    @php($inputId = 'board-game-' . $boardGame->id)
                                    <li>
                                        <label class="genre-dropdown__item" for="{{ $inputId }}">
                                            <input
                                                id="{{ $inputId }}"
                                                type="checkbox"
                                                name="board_games[]"
                                                value="{{ $boardGame->id }}"
                                                class="genre-dropdown__checkbox"
                                                data-genre-label="{{ $boardGame->title }}"
                                                {{ in_array($boardGame->id, $selectedBoardGames, true) ? 'checked' : '' }}
                                            />
                                            <span>{{ $boardGame->title }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
            
                    <div class="genre-selection" data-genre-selected>
                        <div class="genre-selection__header">
                            <p class="genre-selection__title">Wybrane gry</p>
                            <span class="genre-selection__count" data-selected-count>0</span>
                        </div>
                        <div class="genre-selection__list" data-selected-list></div>
                        <p class="genre-selection__empty" data-selected-empty>Brak wybranych gier.</p>
                    </div>
                </div>
            
                <flux:error name="board_games" />
            </flux:field>
            
            <flux:field>
                <div class="genre-picker__intro">
                    <flux:label>Powiązane gatunki</flux:label>
                    <p class="genre-picker__hint">Wybierz gatunki, których dotyczy ten post.</p>
                </div>
            
                <div class="genre-flow" data-genre-select>
                    <div class="genre-dropdown">
                        <button type="button" class="genre-dropdown__trigger" data-genre-toggle>
                            Wybierz gatunki
                            <span class="genre-dropdown__chevron" aria-hidden="true"></span>
                        </button>
            
                        <div class="genre-dropdown__panel" data-genre-panel hidden>
                            <ul class="genre-dropdown__list">
                                @foreach($genres as $genre)
                                    @php($inputId = 'genre-' . $genre->id)
                                    <li>
                                        <label class="genre-dropdown__item" for="{{ $inputId }}">
                                            <input
                                                id="{{ $inputId }}"
                                                type="checkbox"
                                                name="genres[]"
                                                value="{{ $genre->id }}"
                                                class="genre-dropdown__checkbox"
                                                data-genre-label="{{ $genre->name }}"
                                                {{ in_array($genre->id, $selectedGenres, true) ? 'checked' : '' }}
                                            />
                                            <span>{{ $genre->name }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
            
                    <div class="genre-selection" data-genre-selected>
                        <div class="genre-selection__header">
                            <p class="genre-selection__title">Wybrane gatunki</p>
                            <span class="genre-selection__count" data-selected-count>0</span>
                        </div>
                        <div class="genre-selection__list" data-selected-list></div>
                        <p class="genre-selection__empty" data-selected-empty>Brak wybranych gatunków.</p>
                    </div>
                </div>
            
                <flux:error name="genres" />
            </flux:field>

            <div class="flex justify-end gap-4 mt-6">                
                <flux:button href="{{ route('dashboard') }}" variant="ghost">
                    Anuluj
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Opublikuj post
                </flux:button>
            </div>
        </form>
    </section>
    @include('board-games.partials.genre-picker-script')
</x-layouts.app>