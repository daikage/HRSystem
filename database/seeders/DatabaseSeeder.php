<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EmployeeProfile;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // REMOVED: use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Reset Spatie's cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // 3. Create or Update Admin User
        // Using updateOrCreate prevents SQL crash if the user already exists
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@luminahr.com'], // Search by email
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // 4. Assign Role
        $adminUser->assignRole($adminRole);

        // 5. Create or Update Employee Profile
        // Prevents duplicate profile errors on subsequent seeder runs
        EmployeeProfile::updateOrCreate(
            ['user_id' => $adminUser->id], // Search by user_id
            [
                'department' => 'HR',
                'job_title' => 'HR Administrator',
                'joining_date' => now(),
            ]
        );
    }
}