<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ── Default System Admin ────────────────────────────────────────────────
        Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'slug'     => 'system-admin',
                'name'     => 'System Admin',
                'email'    => 'admin@example.com',
                'phone'    => null,
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'status'   => 'active',
            ]
        );

        $this->command->info('Default admin created: admin@example.com / password123');

        // ── Additional Test Admins (Factory) ────────────────────────────────────
        Admin::factory()->count(10)->active()->create();

        $this->command->info('10 additional test admins created.');
    }
}
