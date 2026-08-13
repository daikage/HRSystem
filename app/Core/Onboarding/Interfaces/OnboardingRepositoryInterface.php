<?php

namespace App\Core\Onboarding\Interfaces;

interface OnboardingRepositoryInterface
{
    /**
     * Create a new onboarding request record.
     */
    public function create(array $data);

    /**
     * Find a request by its ID.
     */
    public function findById(int $id);

    /**
     * Find a request by its public UUID.
     */
    public function findByUuid(string $uuid);

    /**
     * Update the status of a request.
     */
    public function updateStatus(int $id, string $status, ?string $adminFeedback = null);

    /**
     * Retrieve all pending requests.
     */
    public function getPending();
}
