<x-modal-card
    title="{{ $form->genre !== null ? __('genres.labels.edit_form_title') : __('genres.labels.create_form_title)) }}"
    wire:model="visible">

    <x-input label="{{__('genres.attributes.name') }}" placeholder="{{ __('Enter value') }}" wire:model="form.name"/>

    <x-slot name="footer" class="flex justify-end">
        <div class="flex gap-x-4">
            <x-button flat label="{{ __('Cancel') }}" wire:click="close"/>

            <x-button primary label="{{ __('Save') }}" wire:click="save" spinner/>
        </div>
    </x-slot>
</x-modal-card>