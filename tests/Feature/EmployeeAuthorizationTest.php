<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EmployeeAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('admin');
        Role::findOrCreate('employee');
    }

    private function adminUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    private function employeeUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('employee');

        return $user;
    }

    public function test_guests_are_redirected_from_employee_directory(): void
    {
        $this->get('/employees')->assertRedirect('/login');
    }

    public function test_admins_can_access_the_employee_create_page(): void
    {
        $this->actingAs($this->adminUser())
            ->get('/employees/create')
            ->assertOk();
    }

    public function test_employees_cannot_access_the_employee_create_page(): void
    {
        $this->actingAs($this->employeeUser())
            ->get('/employees/create')
            ->assertForbidden();
    }

    public function test_employees_cannot_create_employees(): void
    {
        $this->actingAs($this->employeeUser())
            ->post('/employees', $this->validEmployeeData())
            ->assertForbidden();
    }

    public function test_admins_can_create_an_employee(): void
    {
        $this->actingAs($this->adminUser())
            ->post('/employees', $this->validEmployeeData())
            ->assertRedirect(route('employees.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com', 'must_change_password' => true]);
        $user = User::where('email', 'jane@example.com')->firstOrFail();
        $this->assertTrue($user->hasRole('employee'));
        $this->assertEquals('Engineering', $user->employeeProfile->department);
        $this->assertEquals(25, $user->employeeProfile->annual_leave_entitlement);
    }

    private function validEmployeeData(): array
    {
        return [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'department' => 'Engineering',
            'job_title' => 'Developer',
            'salary' => 80000,
            'joining_date' => '2026-01-15',
            'annual_leave_entitlement' => 25,
        ];
    }
}
