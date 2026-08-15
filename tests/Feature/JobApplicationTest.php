<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JobApplicationTest extends TestCase
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

    private function job(): JobListing
    {
        return JobListing::create([
            'title' => 'Frontend Developer',
            'department' => 'Engineering',
            'location' => 'Remote',
            'employment_type' => 'Full-time',
            'description' => 'Build the UI.',
            'status' => 'open',
        ]);
    }

    public function test_guest_can_apply_for_a_job(): void
    {
        $job = $this->job();

        $this->post(route('jobs.apply.submit', $job), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ])
            ->assertRedirect(route('jobs.applied'));

        $this->assertDatabaseHas('job_applications', [
            'job_listing_id' => $job->id,
            'email' => 'jane@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_applications_cannot_be_submitted_for_closed_jobs(): void
    {
        $job = $this->job();
        $job->update(['status' => 'closed']);

        $this->post(route('jobs.apply.submit', $job), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ])->assertNotFound();
    }

    public function test_admin_can_approve_an_application(): void
    {
        $app = JobApplication::create([
            'job_listing_id' => $this->job()->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.job-applications.approve', $app))
            ->assertRedirect(route('admin.job-applications.index'));

        $this->assertEquals('approved', $app->fresh()->status);
    }

    public function test_admin_can_reject_an_application_with_feedback(): void
    {
        $app = JobApplication::create([
            'job_listing_id' => $this->job()->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.job-applications.reject', $app), ['feedback' => 'Role filled.'])
            ->assertRedirect(route('admin.job-applications.index'));

        $this->assertEquals('rejected', $app->fresh()->status);
        $this->assertEquals('Role filled.', $app->fresh()->admin_feedback);
    }
}