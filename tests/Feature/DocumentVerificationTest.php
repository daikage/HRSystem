<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DocumentVerificationTest extends TestCase
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

    public function test_employee_can_upload_a_document_for_verification(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('documents.store'), [
                'title' => 'Passport',
                'category' => 'Passport',
                'document' => UploadedFile::fake()->create('passport.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('documents.index'));

        $this->assertDatabaseHas('user_documents', [
            'user_id' => $user->id,
            'title' => 'Passport',
            'status' => 'pending',
        ]);

        $this->assertCount(1, Storage::disk('local')->allFiles('employee-documents'));
    }

    public function test_employee_cannot_open_document_verification_inbox(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.documents.index'))
            ->assertForbidden();
    }

    public function test_admin_can_approve_a_document(): void
    {
        $user = User::factory()->create();

        $document = UserDocument::create([
            'user_id' => $user->id,
            'title' => 'Passport',
            'category' => 'Passport',
            'file_name' => 'passport.pdf',
            'file_path' => 'employee-documents/passport.pdf',
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.documents.approve', $document))
            ->assertRedirect(route('admin.documents.index'));

        $this->assertEquals('approved', $document->fresh()->status);
    }

    public function test_admin_can_reject_a_document_with_feedback(): void
    {
        $user = User::factory()->create();

        $document = UserDocument::create([
            'user_id' => $user->id,
            'title' => 'Passport',
            'category' => 'Passport',
            'file_name' => 'passport.pdf',
            'file_path' => 'employee-documents/passport.pdf',
            'status' => 'pending',
        ]);

        $this->actingAs($this->adminUser())
            ->post(route('admin.documents.reject', $document), ['feedback' => 'Not readable'])
            ->assertRedirect(route('admin.documents.index'));

        $this->assertEquals('rejected', $document->fresh()->status);
        $this->assertEquals('Not readable', $document->fresh()->admin_feedback);
    }
}