<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\Auth\RoleType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tworzenie 25 losowych użytkowników z rolą USER
        User::factory(25)->create()->each(function ($user) {
            $user->assignRole(RoleType::USER->value);
        });

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole(RoleType::USER->value);

        User::factory()->create([
            'name' => 'Test Worker',
            'email' => 'worker@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole(RoleType::WORKER->value);

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ])->assignRole(RoleType::ADMIN->value);
    }
}
