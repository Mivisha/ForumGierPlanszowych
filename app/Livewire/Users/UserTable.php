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
        $actionButtonClasses = 'pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700';

        return [
            Button::add('assignAdminRoleAction')
                ->slot('<svg class="w-[23px] h-[23px] text-green-600 dark:text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4m-2 2v-4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>')
                ->class($actionButtonClasses)
                ->tooltip(__('users.actions.assign_admin_role'))
                ->dispatch('assignAdminRoleAction', ['id' => $user->id]),

            Button::add('removeAdminRoleAction')
                ->slot('<svg class="w-[23px] h-[23px] text-red-600 dark:text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12h4M4 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>')
                ->class($actionButtonClasses)
                ->tooltip(__('users.actions.remove_admin_role'))
                ->dispatch('removeAdminRoleAction', ['id' => $user->id]),

            Button::add('assignWorkerRoleAction')
                ->slot('<svg class="w-[23px] h-[23px] text-green-900 dark:text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-linejoin="round" stroke-width="2" d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6.25v3.5m1.75-1.75h-3.5"/>
                </svg>')
                ->class($actionButtonClasses)
                ->tooltip(__('users.actions.assign_worker_role'))
                ->dispatch('assignWorkerRoleAction', ['id' => $user->id]),

            Button::add('removeWorkerRoleAction')
                ->slot('<svg class="w-[23px] h-[23px] text-red-600 dark:text-red-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-linejoin="round" stroke-width="2" d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.25 7.75h4.5"/>
                </svg>')
                ->class($actionButtonClasses)
                ->tooltip(__('users.actions.remove_worker_role'))
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
