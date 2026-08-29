<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Platform owner with access to tenants, subscriptions, analytics and billing.',
        ]);

        Role::create([
            'name' => 'Organisation Admin',
            'slug' => 'organisation-admin',
            'description' => 'Manages organisation users, assessments, reports and training.',
        ]);

        Role::create([
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Views team readiness, department reports and risk dashboards.',
        ]);

        Role::create([
            'name' => 'Employee',
            'slug' => 'employee',
            'description' => 'Completes assessments and learning plans and views personal scores.',
        ]);
    }
}
