<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    {{-- Кнопка --}}
    <div class="grid justify-items-stretch py-2">
        <div class="p-4 bg-red-100 text-red-800">
    Logged in user: {{ auth()->user()?->email ?? 'NO USER' }}<br>
    Can create genre? {{ auth()->user()?->can('create', \App\Models\Genre::class) ? 'YES' : 'NO' }}
</div>

        @can('create', App\Models\Genre::class)
        <x-wireui-button
            primary
            label="{{ __('genres.actions.create') }}"
            href="{{ route('genres.create') }}"
            class="justify-self-end"
        />
        @endcan
    </div>

    {{-- Контейнер таблиці --}}
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2">
        <livewire:genres.genre-table />
    </div>
</div>
