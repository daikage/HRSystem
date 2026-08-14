<?php

namespace Tests\Feature;

use App\Models\PayrollRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PayrollTest extends TestCase
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

    private function payrollData(int $userId): array
    {
        return [
            'user_id' => $userId,
            'pay_period_start' => '2026-08-01',
            'pay_period_end' => '2026-08-14',
            'base_salary' => 1000,
            'bonuses' => 200,
            'deductions' => 50,
        ];
    }

    public function test_employees_cannot_create_payroll_records(): void
    {
        $this->actingAs($this->employeeUser())
            ->post('/payroll', $this->payrollData(1))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_payroll_record_with_net_pay(): void
    {
        $employee = $this->employeeUser();

        $this->actingAs($this->adminUser())
            ->post('/payroll', $this->payrollData($employee->id))
            ->assertRedirect(route('payroll.index'));

        $this->assertDatabaseHas('payroll_records', [
            'user_id' => $employee->id,
            'net_pay' => 1150, // 1000 + 200 - 50
            'status' => 'pending',
        ]);
    }

    public function test_overlapping_pay_periods_are_blocked(): void
    {
        $employee = $this->employeeUser();

        $this->actingAs($this->adminUser())
            ->post('/payroll', $this->payrollData($employee->id))
            ->assertRedirect();

        // Same period again should be rejected.
        $this->actingAs($this->adminUser())
            ->post('/payroll', $this->payrollData($employee->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals(1, PayrollRecord::where('user_id', $employee->id)->count());
    }
}
