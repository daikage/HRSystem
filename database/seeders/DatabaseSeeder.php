<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Role
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        
        // Create Employee Role
        $employeeRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'employee']);

        // Create Admin User
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@luminahr.com',
            'password' => bcrypt('password'), // default password
        ]);

        // Assign Role
        $adminUser->assignRole($adminRole);

        // Create a default Employee Profile for the admin
        \App\Models\EmployeeProfile::create([
            'user_id' => $adminUser->id,
            'department' => 'HR',
            'job_title' => 'HR Administrator',
            'joining_date' => now(),
        ]);
    }
}
