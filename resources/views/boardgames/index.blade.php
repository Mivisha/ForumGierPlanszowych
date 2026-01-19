<x-layouts.app :title="__('translation.navigation.boardgames')">
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2">
                <livewire:boardgames.boardgame-table />
            </div>
        </div>
    </div>
</x-layouts.app>