<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'CEO/CFO',
            'Human Resource (HR) Department',
            'Finance & Accounts Department',
            'Sales & Marketing Department',
            'Project Management Department',
            'Education Management Department',
            'Hotel Management Department',
            'Service Management Department',
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate([
                'name' => $dept,
            ], [
                'code' => strtoupper(Str::slug(str_replace(['&', '(', ')'], '', $dept), '_')),
                'slug' => Str::slug($dept),
                'is_system_default' => true,
                'is_active' => true,
            ]);
        }
    }
}
