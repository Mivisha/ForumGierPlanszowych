<?php

namespace App\Livewire\BoardGames;

use App\Models\BoardGame;
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

final class BoardGameTable extends PowerGridComponent
{
    public string $tableName = 'boardGameTable';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->includeViewOnTop('partials.boardgame-heading')
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return BoardGame::query()
        ->with(['genres', ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title')
            ->add('genres', fn (BoardGame $model) => $model->genres->pluck('name')->join(', '))
            ->add('year_published', fn (BoardGame $model) => Carbon::parse($model->year_published)->format('Y'))
            ->add('rating')
            ->add('image_thumbnail', fn (BoardGame $model) => $model->image_path ? '<img src="' . asset('storage/' . $model->image_path) . '" alt="' . e($model->title) . '" class="h-16 w-16 object-cover rounded" />' : '<span class="text-gray-400">-</span>')
            ;
    }

    public function columns(): array
    {
        return [
            Column::make(__('boardgames.attributes.image'), 'image_thumbnail'),
            Column::make(__('boardgames.attributes.title'), 'title')
                ->searchable()
                ->sortable(),
            Column::make(__('boardgames.attributes.genres'), 'genres')
                ->searchable()
                ->sortable(),
            Column::make(__('boardgames.attributes.year_published'), 'year_published')
                ->searchable()
                ->sortable(),
            Column::make(__('boardgames.attributes.rating'), 'rating')
                ->sortable(),
            Column::action(__('boardgames.attributes.action'))
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('title'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }
    #[\Livewire\Attributes\On('toggleFavorite')]
public function toggleFavorite($rowId)
{
    if (!auth()->check()) {
        return;
    }

    $user = auth()->user();
    $boardGame = BoardGame::findOrFail($rowId);

    if ($user->favoriteGames()->where('board_game_id', $boardGame->id)->exists()) {
        $user->favoriteGames()->detach($boardGame->id);
        $message = 'Skasowano z ulubionych';
    } else {
        $user->favoriteGames()->attach($boardGame->id);
        $message = 'Dodano do ulubionych';
    }

    $this->dispatch('flash-message', ['message' => $message, 'type' => 'success']);
    $this->dispatch('pg:eventRefresh-boardGameTable');
}

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId)
    {
        return $this->redirect(route('board-games.edit', $rowId), navigate: true);
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($rowId)
    {
        $boardGame = BoardGame::findOrFail($rowId);
        $this->authorize('delete', $boardGame);
        $boardGame->delete();
        
        $this->dispatch('flash-message', ['message' => __('boardgames.messages.deleted'), 'type' => 'danger']);
        $this->dispatch('pg:eventRefresh-boardGameTable');
    }

    public function actions(BoardGame $row): array
    {
        return [
            Button::add('view')
                ->slot('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-800 dark:border-pg-primary-800 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->tooltip(__('boardgames.actions.view'))
                ->dispatch('openViewModal', ['rowId' => $row->id]),
            Button::add('favorite')
            ->slot(auth()->check() && auth()->user()->favoriteGames()->where('board_game_id', $row->id)->exists() 
                ? '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>'
                : '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>')
            ->id()
            ->class('pg-btn-white dark:ring-pg-primary-800 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
            ->tooltip(__('Додати в улюблені'))
            ->dispatch('toggleFavorite', ['rowId' => $row->id]),
            Button::add('edit')
                ->slot('<svg class="w-5 h-5" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-800 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->tooltip(__('boardgames.actions.edit'))
                ->dispatch('edit', ['rowId' => $row->id]),
            Button::add('delete')
                ->slot('<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>')                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-800 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->tooltip(__('boardgames.actions.delete'))
                ->dispatch('delete', ['rowId' => $row->id])
                ->confirm('Jesteś pewien, że chcesz usunąć tę grę planszową?'),
        ];
    }

    public function actionRules(BoardGame $row): array
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
