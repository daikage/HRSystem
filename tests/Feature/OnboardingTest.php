<?php

namespace Tests\Feature;

use App\Models\OnboardingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OnboardingTest extends TestCase
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

    public function test_approving_onboarding_creates_an_employee_account(): void
    {
        $request = OnboardingRequest::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'credentials_data' => null,
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.onboarding.approve', $request->id))
            ->assertRedirect(route('admin.onboarding.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertEquals('approved', $request->fresh()->status);
    }

    public function test_approving_onboarding_with_duplicate_email_is_blocked(): void
    {
        // The email already belongs to an existing user.
        User::factory()->create(['email' => 'jane@example.com']);

        $request = OnboardingRequest::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'credentials_data' => null,
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.onboarding.approve', $request->id))
            ->assertRedirect(route('admin.onboarding.index'))
            ->assertSessionHas('error');

        // No second user may be created and the request remains pending.
        $this->assertEquals(1, User::where('email', 'jane@example.com')->count());
        $this->assertEquals('pending', $request->fresh()->status);
    }
}
