<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Network Issue', 'description' => 'Internet, LAN, router, connectivity problems'],
            ['name' => 'Printer Issue', 'description' => 'Printer faults and printing problems'],
            ['name' => 'System Issue', 'description' => 'Application or software system problems'],
            ['name' => 'Password Reset', 'description' => 'Account unlock and reset requests'],
            ['name' => 'Email Issue', 'description' => 'Email sending, receiving, or login issues'],
            ['name' => 'Hardware Issue', 'description' => 'Computer, monitor, keyboard, and other hardware faults'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
