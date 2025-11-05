<flux:navlist variant="outline">
    <flux:navlist.group :heading="__('Genres')">
        <flux:navlist.item :href="route('genres.index')" :active="request()->routeIs('genres.index')">
            {{ __('Browse Genres') }}
        </flux:navlist.item>
    </flux:navlist.group>
</flux:navlist>