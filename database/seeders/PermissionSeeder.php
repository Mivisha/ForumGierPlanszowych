<?php

namespace Database\Seeders;

use App\Enums\Auth\PermissionType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        foreach (PermissionType::cases() as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission->value,
                'guard_name' => 'web',
            ]);
        }
        // Permission::create(['name' => PermissionType::USER_ACCESS->value]);
        // Permission::create(['name' => PermissionType::USER_MANAGE->value]);

        // Permission::create(['name' => PermissionType::GENRE_ACCESS->value]);
        // Permission::create(['name' => PermissionType::GENRE_MANAGE->value]);

        // Permission::create(['name' => PermissionType::BOARDGAME_ACCESS->value]);
        // Permission::create(['name' => PermissionType::BOARDGAME_MANAGE->value]);
    }
}
