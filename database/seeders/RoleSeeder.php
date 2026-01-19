<?php

namespace Database\Seeders;

use App\Enums\Auth\PermissionType;
use App\Enums\Auth\RoleType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Uruchomienie konkretnego seedera:
        // sail artisan db:seed --class=RoleSeeder

        // Reset cache'a ról i uprawnień:
        // sail artisan permission:cache-reset
         app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // $admin = Role::firstOrCreate([
        //     'name'       => RoleType::ADMIN->value,
        //     'guard_name' => 'web',
        // ]);

        // $worker = Role::firstOrCreate([
        //     'name'       => RoleType::WORKER->value,
        //     'guard_name' => 'web',
        // ]);

        // $user = Role::firstOrCreate([
        //     'name'       => RoleType::USER->value,
        //     'guard_name' => 'web',
        // ]);

        Role::create(['name' => RoleType::ADMIN]);
        Role::create(['name' => RoleType::WORKER]);
        Role::create(['name' => RoleType::USER]);

        // ADMINISTRATOR SYSTEMU
        $userRole = Role::findByName(RoleType::ADMIN->value);
        $userRole->givePermissionTo(PermissionType::USER_ACCESS->value);
        $userRole->givePermissionTo(PermissionType::USER_MANAGE->value);
        $userRole->givePermissionTo(PermissionType::GENRE_ACCESS->value);
        $userRole->givePermissionTo(PermissionType::GENRE_MANAGE->value);
        $userRole->givePermissionTo(PermissionType::BOARDGAME_ACCESS->value);
        $userRole->givePermissionTo(PermissionType::BOARDGAME_MANAGE->value);

        // PRACOWNIK
        $userRole = Role::findByName(RoleType::WORKER->value);
        $userRole->givePermissionTo(PermissionType::GENRE_ACCESS->value);
        $userRole->givePermissionTo(PermissionType::GENRE_MANAGE->value);
        $userRole->givePermissionTo(PermissionType::BOARDGAME_ACCESS->value);
        $userRole->givePermissionTo(PermissionType::BOARDGAME_MANAGE->value);
        // UŻYTKOWNIK
        $userRole = Role::findByName(RoleType::USER->value);
        $userRole->givePermissionTo(PermissionType::BOARDGAME_ACCESS->value);
    
    //      $admin->syncPermissions([
    //         PermissionType::USER_ACCESS->value,
    //         PermissionType::USER_MANAGE->value,
    //         PermissionType::GENRE_ACCESS->value,
    //         PermissionType::GENRE_MANAGE->value,
    //         PermissionType::BOARDGAME_ACCESS->value,
    //         PermissionType::BOARDGAME_MANAGE->value,
    //     ]);

    //     $worker->syncPermissions([
    //         PermissionType::GENRE_ACCESS->value,
    //         PermissionType::GENRE_MANAGE->value,
    //         PermissionType::BOARDGAME_ACCESS->value,
    //         PermissionType::BOARDGAME_MANAGE->value,
    //     ]);

    //     $user->syncPermissions([
    //         PermissionType::GENRE_ACCESS->value,
    //         PermissionType::BOARDGAME_ACCESS->value,
    //     ]);    
    }
}
