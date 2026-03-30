<?php

namespace App\Livewire\Genres;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class GenreTable extends PowerGridComponent
{
    public string $tableName = 'genreTable';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->includeViewOnTop('partials.genre-heading')
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Genre::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('name_lower', fn (Genre $model) => strtolower(e($model->name)))
            ->add('created_at')
            ->add('created_at_formatted', fn (Genre $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make(__('genres.attributes.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('genres.attributes.name'), 'name')
                ->searchable()
                ->sortable(),

            Column::make(__('genres.attributes.created_at'), 'created_at')
                ->hidden(),

            Column::make(__('genres.attributes.created_at'), 'created_at_formatted', 'created_at')
                ->searchable(),

            Column::action(__('genres.attributes.action'))
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId)
    {
        return $this->redirect(route('genres.edit', $rowId), navigate: true);
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($rowId)
    {
        $genre = Genre::findOrFail($rowId);
        $this->authorize('delete', $genre);
        $genre->delete();
        
        $this->dispatch('flash-message', ['message' => __('genres.messages.deleted'), 'type' => 'danger']);
        $this->dispatch('pg:eventRefresh-genreTable');
    }

    public function actions(Genre $row): array
    {
        return [
            Button::add('edit')
                ->slot('<svg class="w-5 h-5" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-800 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->tooltip(__('genres.actions.edit'))
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->slot('<svg class="w-5 h-5 text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>')                ->id()
                ->class('pg-btn-white text-red-500 dark:ring-pg-primary-800 dark:border-pg-primary-600 dark:hover:bg-red-700 dark:hover:text-white dark:ring-offset-pg-primary-800')
                ->tooltip(__('genres.actions.delete'))
                ->dispatch('delete', ['rowId' => $row->id])
                ->confirm('Czy na pewno chcesz usunąć ten gatunek?')
        ];
    }

    public function actionRules(Genre $row): array
    {
       return [
            Rule::button('edit')
                ->when(fn($row) => !Auth::user()->can('update', $row))
                ->hide(),
            Rule::button('delete')
                ->when(fn($row) => !Auth::user()->can('delete', $row))
                ->hide(),
        ];
    }
}
