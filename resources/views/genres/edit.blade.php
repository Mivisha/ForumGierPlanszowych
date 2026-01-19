<x-layouts.app :title="__('Genres')">
    <section class="w-full max-w-2xl">
        <form method="POST" action="{{ route('genres.update', $genre) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <flux:heading>{{ __('Edytuj gatunek') }}</flux:heading>
            </div>

            <flux:field>
                <flux:label>{{ __('genres.attributes.name') }}</flux:label>
                <flux:input name="name" placeholder="{{ __('Wpisz nazwę gatunku') }}" value="{{ old('name', $genre->name) }}" />
                <flux:error name="name" />
            </flux:field>

            <div class="flex justify-end gap-4 mt-6">
                <flux:button href="{{ route('genres.index') }}" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </section>
</x-layouts.app>
