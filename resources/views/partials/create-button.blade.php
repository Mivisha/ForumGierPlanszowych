{{ auth()->user()->can('create', \App\Models\Genre::class) ? 'CAN CREATE' : 'CANNOT CREATE' }}

@can('create', App\Models\Genre::class)
    <x-wireui-button primary label="{{ __('genres.actions.create') }}"
    href="{{ route('genres.create') }}" class="justify-self-end" />
@endcan