<?php

namespace App\Core\Onboarding\Repositories;

use App\Core\Onboarding\Interfaces\OnboardingRepositoryInterface;
use App\Models\OnboardingRequest;

class OnboardingRepository implements OnboardingRepositoryInterface
{
    public function create(array $data)
    {
        return OnboardingRequest::create($data);
    }

    public function findById(int $id)
    {
        return OnboardingRequest::findOrFail($id);
    }

    public function findByUuid(string $uuid)
    {
        return OnboardingRequest::where('uuid', $uuid)->firstOrFail();
    }

    public function updateStatus(int $id, string $status, ?string $adminFeedback = null)
    {
        $request = $this->findById($id);
        $request->status = $status;
        if ($adminFeedback !== null) {
            $request->admin_feedback = $adminFeedback;
        }
        $request->save();
        return $request;
    }

    public function getPending()
    {
        return OnboardingRequest::where('status', 'pending')->latest()->paginate(10);
    }
}
