<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JobListingTest extends TestCase
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

    public function test_admin_can_create_a_job_listing(): void
    {
        $this->actingAs($this->adminUser())
            ->post(route('admin.jobs.store'), [
                'title' => 'Backend Developer',
                'department' => 'Engineering',
                'location' => 'Remote',
                'employment_type' => 'Full-time',
                'salary_min' => 50000,
                'salary_max' => 70000,
                'description' => 'Build great things.',
                'requirements' => 'PHP and Laravel experience.',
            ])
            ->assertRedirect(route('admin.jobs.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('job_listings', [
            'title' => 'Backend Developer',
            'status' => 'open',
        ]);
    }

    public function test_closed_jobs_are_not_listed_publicly(): void
    {
        JobListing::create([
            'title' => 'Hidden Role',
            'department' => 'Engineering',
            'location' => 'Remote',
            'employment_type' => 'Full-time',
            'description' => 'Test',
            'status' => 'closed',
        ]);

        $this->get(route('jobs.index'))
            ->assertOk()
            ->assertDontSee('Hidden Role');
    }

    public function test_employees_cannot_create_job_listings(): void
    {
        $this->actingAs($this->employeeUser())
            ->get(route('admin.jobs.create'))
            ->assertForbidden();
    }

    public function test_admin_can_toggle_job_status(): void
    {
        $job = JobListing::create([
            'title' => 'Active Role',
            'department' => 'Engineering',
            'location' => 'Remote',
            'employment_type' => 'Full-time',
            'description' => 'Test',
            'status' => 'open',
        ]);

        $this->actingAs($this->adminUser())
            ->patch(route('admin.jobs.status', $job))
            ->assertRedirect(route('admin.jobs.index'));

        $this->assertEquals('closed', $job->fresh()->status);
    }
}