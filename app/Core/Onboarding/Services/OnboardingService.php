<?php

namespace App\Core\Onboarding\Services;

use App\Core\Onboarding\Interfaces\OnboardingRepositoryInterface;
use App\Core\Onboarding\Interfaces\OnboardingServiceInterface;
use Illuminate\Support\Str;

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
        // Add logic here later to create a User account and Employee Profile
        return $this->repository->updateStatus($requestId, 'approved');
    }

    public function rejectRequest(int $requestId, string $reason)
    {
        return $this->repository->updateStatus($requestId, 'rejected', $reason);
    }

    public function getPendingRequests()
    {
        return $this->repository->getPending();
    }
}
