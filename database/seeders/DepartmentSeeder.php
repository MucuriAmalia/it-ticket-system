<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'ICT',
                'description' => 'Handles systems, network, hardware, software, and technical support issues.',
            ],
            [
                'name' => 'HR',
                'description' => 'Handles human resource and staff-related operations.',
            ],
            [
                'name' => 'Finance',
                'description' => 'Handles finance, payments, accounting, and related operations.',
            ],
            [
                'name' => 'Operations',
                'description' => 'Handles day-to-day operational activities across the organization.',
            ],
            [
                'name' => 'Customer Care',
                'description' => 'Handles customer support and customer-facing service processes.',
            ],
            [
                'name' => 'Administration',
                'description' => 'Handles office administration and internal coordination tasks.',
            ],
            [
                'name' => 'Legal',
                'description' => 'Handles legal, compliance, and contractual matters.',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Handles marketing, communications, and promotions.',
            ],
            [
                'name' => 'Audit',
                'description' => 'Handles internal audit and control review functions.',
            ],
            [
                'name' => 'Credit',
                'description' => 'Handles credit assessment and related credit operations.',
            ],
            [
                'name' => 'Registry',
                'description' => 'Handles records, filing, and document registry functions.',
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['name' => $department['name']],
                ['description' => $department['description']]
            );
        }
    }
}