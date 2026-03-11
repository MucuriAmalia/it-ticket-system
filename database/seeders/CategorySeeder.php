<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Network Issue', 'description' => 'Internet, LAN, router, and connectivity problems'],
            ['name' => 'Printer Issue', 'description' => 'Printer faults, paper jams, and printing problems'],
            ['name' => 'System Issue', 'description' => 'Application, software, or system errors'],
            ['name' => 'Password Reset', 'description' => 'Account unlock and password reset requests'],
            ['name' => 'Email Issue', 'description' => 'Email sending, receiving, or login issues'],
            ['name' => 'Hardware Issue', 'description' => 'Computer, monitor, keyboard, and other hardware faults'],
            ['name' => 'Telephone Issue', 'description' => 'Desk phone, extension, and call connectivity problems'],
            ['name' => 'Power Issue', 'description' => 'Power outages, UPS, and socket-related problems'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}