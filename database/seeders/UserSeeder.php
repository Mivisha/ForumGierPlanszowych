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

        User::factory(25)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('12345678'),
        ]);


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'mainteiner@example.com',
            'password' => Hash::make('12345678'),
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
