<?php

namespace Tests\Feature;

use App\Models\EmployeeProfile;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('admin');
        Role::findOrCreate('employee');
    }

    private function employeeUser(int $entitlement = 20): User
    {
        $user = User::factory()->create();
        $user->assignRole('employee');
        EmployeeProfile::create([
            'user_id' => $user->id,
            'department' => 'Engineering',
            'job_title' => 'Developer',
            'joining_date' => now(),
            'annual_leave_entitlement' => $entitlement,
        ]);

        return $user;
    }

    private function adminUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_employee_can_submit_a_leave_request(): void
    {
        $user = $this->employeeUser();

        $this->actingAs($user)
            ->post('/leave-requests', [
                'type' => 'Annual',
                'start_date' => now()->addDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
                'reason' => 'Vacation',
            ])
            ->assertRedirect(route('leave-requests.index'));

        $this->assertDatabaseHas('leave_requests', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function test_overlapping_leave_requests_are_blocked(): void
    {
        $user = $this->employeeUser();

        LeaveRequest::create([
            'user_id' => $user->id,
            'type' => 'Annual',
            'start_date' => now()->addDays(10)->toDateString(),
            'end_date' => now()->addDays(12)->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->post('/leave-requests', [
                'type' => 'Annual',
                'start_date' => now()->addDays(11)->toDateString(),
                'end_date' => now()->addDays(13)->toDateString(),
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals(1, LeaveRequest::where('user_id', $user->id)->count());
    }

    public function test_annual_leave_cannot_exceed_remaining_balance(): void
    {
        // Entitlement of 2 days, requesting 3.
        $user = $this->employeeUser(2);

        $this->actingAs($user)
            ->post('/leave-requests', [
                'type' => 'Annual',
                'start_date' => now()->addDays(5)->toDateString(),
                'end_date' => now()->addDays(7)->toDateString(),
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals(0, LeaveRequest::where('user_id', $user->id)->count());
    }

    public function test_employees_cannot_approve_leave_requests(): void
    {
        $user = $this->employeeUser();
        $leave = LeaveRequest::create([
            'user_id' => $user->id,
            'type' => 'Annual',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->patch('/leave-requests/'.$leave->id.'/status', ['status' => 'approved'])
            ->assertForbidden();

        $this->assertDatabaseHas('leave_requests', ['id' => $leave->id, 'status' => 'pending']);
    }

    public function test_admin_can_approve_leave_requests(): void
    {
        $user = $this->employeeUser();
        $leave = LeaveRequest::create([
            'user_id' => $user->id,
            'type' => 'Annual',
            'start_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(6)->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->patch('/leave-requests/'.$leave->id.'/status', ['status' => 'approved'])
            ->assertRedirect();

        $this->assertDatabaseHas('leave_requests', ['id' => $leave->id, 'status' => 'approved']);
    }
}
