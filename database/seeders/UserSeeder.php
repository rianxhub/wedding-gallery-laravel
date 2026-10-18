<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        User::firstOrCreate(
            ['email' => 'admin@weddinggallery.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Client User 1
        User::firstOrCreate(
            ['email' => 'rian.aulia@gmail.com'],
            [
                'name' => 'Rian & Aulia',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );

        // 3. Client User 2
        User::firstOrCreate(
            ['email' => 'dimas.sarah@gmail.com'],
            [
                'name' => 'Dimas & Sarah',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
    }
}
