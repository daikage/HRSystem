<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::findOrCreate('employee');
    }

    private function employeeUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('employee');

        return $user;
    }

    public function test_user_can_clock_in(): void
    {
        $user = $this->employeeUser();

        $this->actingAs($user)
            ->post('/attendance/clock-in')
            ->assertRedirect();

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);
    }

    public function test_user_cannot_clock_in_twice_in_a_day(): void
    {
        $user = $this->employeeUser();

        $this->actingAs($user)->post('/attendance/clock-in')->assertRedirect();
        $this->actingAs($user)->post('/attendance/clock-in')->assertRedirect()->assertSessionHas('error');

        $this->assertEquals(
            1,
            AttendanceRecord::where('user_id', $user->id)->count()
        );
    }

    public function test_user_cannot_clock_out_without_clocking_in_first(): void
    {
        $user = $this->employeeUser();

        $this->actingAs($user)
            ->post('/attendance/clock-out')
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_unique_constraint_prevents_duplicate_daily_records(): void
    {
        $user = $this->employeeUser();

        // Bypass the controller to prove the DB constraint alone is safe.
        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => now()->toTimeString(),
        ]);

        try {
            AttendanceRecord::create([
                'user_id' => $user->id,
                'date' => now()->toDateString(),
                'clock_out' => now()->toTimeString(),
            ]);
            $this->fail('Expected a unique constraint violation.');
        } catch (\Illuminate\Database\QueryException $e) {
            $this->assertEquals(
                1,
                AttendanceRecord::where('user_id', $user->id)->count()
            );
        }
    }
}
