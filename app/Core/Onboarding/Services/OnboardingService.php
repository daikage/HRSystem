<?php

namespace App\Core\Onboarding\Services;

use App\Core\Onboarding\Interfaces\OnboardingRepositoryInterface;
use App\Core\Onboarding\Interfaces\OnboardingServiceInterface;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OnboardingService implements OnboardingServiceInterface
{
    protected $repository;

    public function __construct(OnboardingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function submitRequest(array $data)
    {
        $data['uuid'] = (string) Str::uuid();
        $data['status'] = 'pending';
        return $this->repository->create($data);
    }

    public function approveRequest(int $requestId)
    {
        return DB::transaction(function () use ($requestId) {
            $request = $this->repository->findById($requestId);
            
            if (!$request || $request->status !== 'pending') {
                throw new \Exception('Invalid or already processed onboarding request.');
            }

            // Generate password
            $generatedPassword = Str::random(12);

            // Create User
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($generatedPassword),
                'must_change_password' => true,
            ]);

            // Assign role
            $user->assignRole('employee');

            // Create Profile
            $user->employeeProfile()->create([
                'department' => 'Pending Assignment',
                'job_title' => 'Pending Assignment',
                'joining_date' => now(),
            ]);

            // Update status
            $this->repository->updateStatus($requestId, 'approved');

            return $generatedPassword;
        });
    }

    public function rejectRequest(int $requestId, string $reason)
    {
        return $this->repository->updateStatus($requestId, 'rejected', $reason);
    }

    public function requestInfo(int $requestId, string $message)
    {
        return $this->repository->updateStatus($requestId, 'info_requested', $message);
    }

    public function getPendingRequests()
    {
        return $this->repository->getPending();
    }
}
