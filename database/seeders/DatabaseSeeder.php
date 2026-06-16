<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => User::ROLE_STAFF,
        ]);

        User::factory()->create([
            'name' => 'Accounting Manager',
            'email' => 'manager@example.com',
            'role' => User::ROLE_MANAGER,
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Accounting Supervisor',
            'email' => 'supervisor@example.com',
            'role' => User::ROLE_SUPERVISOR,
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Accounting Staff',
            'email' => 'staff@example.com',
            'role' => User::ROLE_STAFF,
            'password' => bcrypt('password'),
        ]);
    }
}
