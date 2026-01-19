<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;


use App\Enums\Auth\RoleType;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


final class UserTable extends PowerGridComponent
{
    public string $tableName = 'userTable';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->includeViewOnTop('partials.user-heading')
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()->with('roles');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('name_lower', fn (User $model) => strtolower(e($model->name)))
            ->add('created_at')
            ->add('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->add('joined_roles', function ($user){
                return $user->roles->pluck('name')
                ->map(function($roleName){
                    return __('translation.roles.' . $roleName);
                })->join(', ');
            })
            // ->add('is_admin', fn (User $model) => $model->isAdmin())
            // ->add('is_worker', fn (User $model) => $model->isWorker())
            ;
    }

    public function columns(): array
    {
        return [
            Column::make(__('users.attributes.id'), 'id')
                ->searchable()
                ->sortable(),

            Column::make(__('users.attributes.name'), 'name')
                ->searchable()
                ->sortable(),

            Column::make(__('users.attributes.created_at'), 'created_at')
                ->hidden(),

            Column::make(__('users.attributes.roles'), 'joined_roles')
                ->searchable(),
            Column::action(__('users.attributes.action'))
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('assignAdminRoleAction')]
    public function assignAdminRoleAction($id): void
    {
        User::findOrFail($id)->assignRole(RoleType::ADMIN->value);
    }
    #[\Livewire\Attributes\On('removeAdminRoleAction')]
    public function removeAdminRoleAction($id): void
    {
        $this->authorize('update', Auth::user());
        User::findOrFail($id)->removeRole(RoleType::ADMIN->value);
    }

    #[\Livewire\Attributes\On('assignWorkerRoleAction')]
    public function assignWorkerRoleAction($id): void
    {
        $this->authorize('update', Auth::user());
        User::findOrFail($id)->assignRole(RoleType::WORKER->value);
    }

    #[\Livewire\Attributes\On('removeWorkerRoleAction')]
    public function removeWorkerRoleAction($id): void
    {
        $this->authorize('update', Auth::user());
        User::findOrFail($id)->removeRole(RoleType::WORKER->value);
    }

    public function actions(User $user): array
    {
        return [
            Button::add('assignAdminRoleAction')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3 3 3 0 0 0 3-3V4a3 3 0 0 0-3-3zm7 14c-1.1 0-2 .9-2 2v3h-2v-3c0-1.1-.9-2-2-2s-2 .9-2 2v3H9v-3c0-1.1-.9-2-2-2s-2 .9-2 2v6c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-6c0-1.1-.9-2-2-2z"/></svg>')
                ->tooltip(__('users.actions.assign_admin_role'))
                ->class('text-amber-500 hover:text-amber-700')
                ->dispatch('assignAdminRoleAction', ['id' => $user->id]),
            Button::add('removeAdminRoleAction')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3 3 3 0 0 0 3-3V4a3 3 0 0 0-3-3zm0 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm9-13c-.55 0-1 .45-1 1v7c0 1.66-1.34 3-3 3H7c-1.66 0-3-1.34-3-3v-7c0-.55-.45-1-1-1s-1 .45-1 1v7c0 2.76 2.24 5 5 5h10c2.76 0 5-2.24 5-5v-7c0-.55-.45-1-1-1z"/></svg>')
                ->tooltip(__('users.actions.remove_admin_role'))
                ->class('text-red-500 hover:text-red-700')
                ->dispatch('removeAdminRoleAction', ['id' => $user->id]),
            Button::add('assignWorkerRoleAction')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>')
                ->tooltip(__('users.actions.assign_worker_role'))
                ->class('text-blue-500 hover:text-blue-700')
                ->dispatch('assignWorkerRoleAction', ['id' => $user->id]),
            Button::add('removeWorkerRoleAction')
                ->slot('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M19 6.4L17.6 5 12 10.6 6.4 5 5 6.4 10.6 12 5 17.6l1.4 1.4L12 13.4l5.6 5.6 1.4-1.4L13.4 12z"/></svg>')
                ->tooltip(__('users.actions.remove_worker_role'))
                ->class('text-red-500 hover:text-red-700')
                ->dispatch('removeWorkerRoleAction', ['id' => $user->id]),
        ];
    }

    
    public function actionRules($user): array
    {
       return [
            Rule::button('assignAdminRoleAction')
                ->when(fn($user) => $user->isAdmin())
                ->hide(),
            Rule::button('removeAdminRoleAction')
                ->when(fn($user) => !$user->isAdmin())
                ->hide(),
            Rule::button('assignWorkerRoleAction')
                ->when(fn($user) => $user->isWorker())
                ->hide(),
            Rule::button('removeWorkerRoleAction')
                ->when(fn($user) => !$user->isWorker())
                ->hide(),
        ];
    }
    
}
