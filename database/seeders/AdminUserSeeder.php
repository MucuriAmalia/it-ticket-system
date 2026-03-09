<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'aron@helpdesk.com'],
            [
                'name' => 'Aron',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kamau@helpdesk.com'],
            [
                'name' => 'Kamau',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'evans@helpdesk.com'],
            [
                'name' => 'Evans',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sydney@helpdesk.com'],
            [
                'name' => 'Sydney',
                'password' => Hash::make('password'),
                'role' => 'technician',
            ]
        );
    }
}