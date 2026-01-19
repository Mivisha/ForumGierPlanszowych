# Laravel Board Games Management System - AI Coding Guidelines

## Project Overview
Laravel application for managing board games and genres with role-based access control (RBAC) using **Spatie Laravel Permission** package. Three roles exist: **Admin** (full access), **Worker** (genre & board-game management), **User** (read-only access).

## Architecture & Key Components

### Authorization Pattern
- **Role-based**: Admin, Worker, User (defined in [app/Enums/Auth/RoleType.php](../app/Enums/Auth/RoleType.php))
- **Permission-based**: 6 permissions in [app/Enums/Auth/PermissionType.php](../app/Enums/Auth/PermissionType.php)
  - `user_access` / `user_manage` (Admin only)
  - `genre_access` / `genre_manage` (Admin, Worker; access for User)
  - `boardgame_access` / `boardgame_manage` (Admin, Worker; access for User)
- **Policy classes** in [app/Policies/](../app/Policies/) check permissions via `$user->can()` - use these for authorization
- **Seeding**: [database/seeders/RoleSeeder.php](../database/seeders/RoleSeeder.php) assigns permissions to roles

### Livewire Component Structure
- **PowerGrid tables** for data display: [UserTable.php](../app/Livewire/Users/UserTable.php), [GenreTable.php](../app/Livewire/Genres/GenreTable.php), [BoardGameTable.php](../app/Livewire/BoardGames/BoardGameTable.php)
- Event-driven actions via `#[\Livewire\Attributes\On('actionName')]`
- Livewire Volt for simple blade components

### UI Framework
- **Flux UI** components for navigation, dropdowns, tables (zero-JavaScript requirement)
- Sidebar navigation in [resources/views/components/layouts/app/sidebar.blade.php](../resources/views/components/layouts/app/sidebar.blade.php)
- **⚠️ CRITICAL**: Navigation items currently show to ALL users - they must check `can()` permission before rendering

## Critical Issue: Missing Authorization in Navigation

**Problem**: [sidebar.blade.php](../resources/views/components/layouts/app/sidebar.blade.php) shows Users, Board Games, Genres tabs to everyone, but route middleware doesn't prevent access.

**Fix Pattern** (use in sidebar and header navigation):
```blade
@can('viewAny', App\Models\User::class)
    <flux:navlist.item icon="user" :href="route('users.index')" wire:navigate>
        {{ __('Users') }}
    </flux:navlist.item>
@endcan

@can('viewAny', App\Models\Genre::class)
    <flux:navlist.item icon="puzzle-piece" :href="route('genres.index')" wire:navigate>
        {{ __('Genres') }}
    </flux:navlist.item>
@endcan
```

Routes and controllers already have `['auth']` middleware but lack explicit policy-based authorization. Use Gate helpers `@can`, `@cannot`, or `authorize()` method in Livewire components.

## Development Workflows

### Running Tests
```bash
sail artisan test                          # Run all tests
sail artisan test --filter=UserPolicyTest # Specific test
```

### Database & Permissions
```bash
sail artisan migrate                    # Apply migrations
sail artisan db:seed --class=RoleSeeder # Reseed permissions (clears cache)
sail artisan permission:cache-reset     # Reset Spatie permission cache
```

### Building Frontend
```bash
npm run dev  # Watch CSS/JS with Tailwind + Vite
npm run build # Production build
```

## Key Files Reference

| File | Purpose |
|------|---------|
| [app/Policies/](../app/Policies/) | Authorization rules per model |
| [app/Models/User.php](../app/Models/User.php) | `HasRoles` trait from Spatie |
| [database/seeders/RoleSeeder.php](../database/seeders/RoleSeeder.php) | Permission assignments |
| [resources/views/components/layouts/app/sidebar.blade.php](../resources/views/components/layouts/app/sidebar.blade.php) | Main navigation - **needs permission guards** |
| [routes/web.php](../routes/web.php) | Route definitions with auth middleware |

## Livewire Authorization in Components

Always authorize actions before processing:
```php
// In Livewire component method
public function someAction($id): void
{
    $this->authorize('update', Auth::user());
    // Process action
}
```

Example in [UserTable.php](../app/Livewire/Users/UserTable.php):
```php
#[\Livewire\Attributes\On('removeAdminRoleAction')]
public function removeAdminRoleAction($id): void
{
    $this->authorize('update', Auth::user()); // Checks UserPolicy::update()
    User::findOrFail($id)->removeRole(RoleType::ADMIN->value);
}
```

## Configuration Notes
- Spatie Permission caching: 24 hours (see [config/permission.php](../config/permission.php))
- Cache is auto-cleared when permissions change
- Teams feature disabled (`config/permission.php`: `'teams' => false`)
