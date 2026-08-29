<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@example.com',
            'password' => 'Password123!',
            'role_id' => 1,
        ]);

        User::factory()->create([
            'name' => 'Organisation Admin',
            'username' => 'organisationadmin',
            'email' => 'organisation@example.com',
            'password' => 'Password123!',
            'role_id' => 2,
        ]);

        User::factory()->create([
            'name' => 'Manager',
            'username' => 'manager',
            'email' => 'manager@example.com',
            'password' => 'Password123!',
            'role_id' => 3,
        ]);

        User::factory()->create([
            'name' => 'Employee',
            'username' => 'employee',
            'email' => 'employee@example.com',
            'password' => 'Password123!',
            'role_id' => 4,
        ]);
    }

}


