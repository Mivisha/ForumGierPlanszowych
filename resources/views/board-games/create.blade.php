<x-layouts.app :title="__('Board Games')">
    <section class="w-full max-w-2xl">
        <form method="POST" action="{{ route('board-games.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <flux:heading>{{ __('boardgames.form.create_title') }}</flux:heading>
            </div>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.title') }}</flux:label>
                <flux:input name="title" placeholder="{{ __('boardgames.placeholders.title') }}" value="{{ old('title') }}" />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.description') }}</flux:label>
                <flux:textarea name="description" placeholder="{{ __('boardgames.placeholders.description') }}">{{ old('description') }}</flux:textarea>
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.age_recommendation') }}</flux:label>
                <flux:input type="number" name="age_recommendation" placeholder="{{ __('boardgames.placeholders.age_recommendation') }}" value="{{ old('age_recommendation') }}" />
                <flux:error name="age_recommendation" />
            </flux:field>

            <div class="grid grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>{{ __('boardgames.attributes.min_players') }}</flux:label>
                    <flux:input type="number" name="min_players" placeholder="{{ __('boardgames.placeholders.min_players') }}" value="{{ old('min_players') }}" />
                    <flux:error name="min_players" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('boardgames.attributes.max_players') }}</flux:label>
                    <flux:input type="number" name="max_players" placeholder="{{ __('boardgames.placeholders.max_players') }}" value="{{ old('max_players') }}" />
                    <flux:error name="max_players" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.play_time_minutes') }}</flux:label>
                <flux:input type="number" name="play_time_minutes" placeholder="{{ __('boardgames.placeholders.play_time_minutes') }}" value="{{ old('play_time_minutes') }}" />
                <flux:error name="play_time_minutes" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.year_published') }}</flux:label>
                <flux:input type="number" name="year_published" placeholder="{{ __('boardgames.placeholders.year_published') }}" value="{{ old('year_published') }}" />
                <flux:error name="year_published" />
            </flux:field>

            <flux:field>
                <flux:label>{{ __('boardgames.attributes.publisher') }}</flux:label>
                <flux:input name="publisher" placeholder="{{ __('boardgames.placeholders.publisher') }}" value="{{ old('publisher') }}" />
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
                <flux:button href="{{ route('board-games.index') }}" variant="ghost">{{ __('boardgames.form.cancel') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ __('boardgames.form.save') }}</flux:button>
            </div>
        </form>
    </section>
</x-layouts.app>
