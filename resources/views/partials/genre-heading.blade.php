{{-- Flash Messages --}}
<div x-data="{ 
    show: @js(session()->has('flash')), 
    message: @js(session('flash.message', '')),
    type: @js(session('flash.type', 'success'))
}" 
     @flash-message.window="show = true; message = $event.detail[0]?.message || $event.detail.message; type = $event.detail[0]?.type || $event.detail.type || 'success'; setTimeout(() => show = false, 5000)"
     x-show="show" 
     x-init="if(show) setTimeout(() => show = false, 5000)"
     :class="{
         'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': type === 'success',
         'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200': type === 'warning',
         'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200': type === 'danger'
     }"
     class="mb-4 p-4 rounded-lg flex items-center justify-between"
     style="display: none;"
     x-style="display: show ? '' : 'none'"
     class="mb-4 p-4 rounded-lg flex items-center justify-between">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span x-text="message"></span>
    </div>
    <button @click="show = false" 
            :class="{
                'text-green-800 dark:text-green-200 hover:text-green-900': type === 'success',
                'text-yellow-800 dark:text-yellow-200 hover:text-yellow-900': type === 'warning',
                'text-red-800 dark:text-red-200 hover:text-red-900': type === 'danger'
            }">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>

<div class="mb-4 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold">{{ __('translation.navigation.genres') }}</h1>
    </div>
    
    @can('create', App\Models\Genre::class)
        <flux:button 
            variant="primary" 
            icon="plus"
            :href="route('genres.create')" 
            wire:navigate>
            {{ __('genres.actions.create') }}
        </flux:button>
    @endcan
</div>
