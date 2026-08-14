<?php

namespace App\Core\Onboarding\Interfaces;

interface OnboardingServiceInterface
{
    /**
     * Submit a new onboarding request.
     */
    public function submitRequest(array $data);

    /**
     * Approve an onboarding request.
     */
    public function approveRequest(int $requestId);

    /**
     * Reject an onboarding request.
     */
    public function rejectRequest(int $requestId, string $reason);

    /**
     * Request additional information from the applicant.
     */
    public function requestInfo(int $requestId, string $message);

    /**
     * Get pending requests for admin review.
     */
    public function getPendingRequests();
}
