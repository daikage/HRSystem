<?php

namespace App\Http\Controllers\Admin;

use App\Core\Onboarding\Interfaces\OnboardingServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingReviewController extends Controller
{
    protected $onboardingService;

    public function __construct(OnboardingServiceInterface $onboardingService)
    {
        $this->onboardingService = $onboardingService;
    }

    public function index()
    {
        $requests = $this->onboardingService->getPendingRequests();
        return view('admin.onboarding.index', compact('requests'));
    }

    public function approve(Request $request, $id)
    {
        $this->onboardingService->approveRequest($id);
        return redirect()->route('admin.onboarding.index')->with('success', 'Onboarding request approved.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);
        $this->onboardingService->rejectRequest($id, $validated['reason']);
        return redirect()->route('admin.onboarding.index')->with('success', 'Onboarding request rejected.');
    }
}
