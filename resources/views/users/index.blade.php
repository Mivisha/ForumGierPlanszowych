<x-layouts.app :title="__('Users')">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-2">
                <livewire:users.user-table />
            </div>
        </div>
    </div>
</x-layouts.app>
