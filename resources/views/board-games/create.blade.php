<x-layouts.app :title="__('Board Games')">
    <section class="w-full max-w-2xl">
        <form method="POST" action="{{ route('board-games.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <flux:heading>{{ __('Create Board Game') }}</flux:heading>
            </div>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.title') }}</flux:label>
                <flux:input name="title" placeholder="{{ __('Enter board game title') }}" value="{{ old('title') }}" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.description') }}</flux:label>
                <flux:textarea name="description" placeholder="{{ __('Enter board game description') }}">{{ old('description') }}</flux:textarea>
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.age_recommendation') }}</flux:label>
                <flux:input type="number" name="age_recommendation" placeholder="{{ __('Minimum age') }}" value="{{ old('age_recommendation') }}" />
                <flux:error name="age_recommendation" />
            </flux:field>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Min Players</flux:label>
                    <flux:input type="number" name="min_players" placeholder="1" value="{{ old('min_players') }}" />
                    <flux:error name="min_players" />
                </flux:field>

                <flux:field>
                    <flux:label>Max Players</flux:label>
                    <flux:input type="number" name="max_players" placeholder="2" value="{{ old('max_players') }}" />
                    <flux:error name="max_players" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Play Time (minutes)</flux:label>
                <flux:input type="number" name="play_time_minutes" placeholder="0" value="{{ old('play_time_minutes') }}" />
                <flux:error name="play_time_minutes" />
            </flux:field>

            <flux:field>
                <flux:label>Year Published</flux:label>
                <flux:input type="number" name="year_published" placeholder="{{ date('Y') }}" value="{{ old('year_published') }}" />
                <flux:error name="year_published" />
            </flux:field>

            <flux:field>
                <flux:label>Publisher</flux:label>
                <flux:input name="publisher" placeholder="Publisher name" value="{{ old('publisher') }}" />
                <flux:error name="publisher" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.image') }}</flux:label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-zinc-300 rounded-lg dark:bg-zinc-900 dark:border-zinc-600" />
                <flux:error name="image" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.genres') }}</flux:label>
                <select name="genres[]" multiple class="flux-input">
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" 
                            {{ in_array($genre->id, old('genres', [])) ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
                <flux:error name="genres" />
            </flux:field>

            <div class="flex justify-end gap-4 mt-6">
                <flux:button href="{{ route('board-games.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </section>
</x-layouts.app>
