<?php

namespace App\Http\Controllers\Admin;

use App\Core\Onboarding\Interfaces\OnboardingServiceInterface;
use App\Http\Controllers\Controller;
use App\Notifications\AccountWelcomeNotification;
use App\Notifications\OnboardingStatusNotification;
use App\Models\OnboardingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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
        try {
            $onboarding = OnboardingRequest::findOrFail($id);
            $password = $this->onboardingService->approveRequest($id);

            // Notify the newly-created employee.
            Notification::route('mail', $onboarding->email)->notify(
                (new OnboardingStatusNotification($onboarding->first_name.' '.$onboarding->last_name, 'approved'))
                    ->onQueue('notifications')
            );
            Notification::route('mail', $onboarding->email)->notify(
                new AccountWelcomeNotification($password)
            );

            return redirect()->route('admin.onboarding.index')->with('success', 'Onboarding request approved. Welcome email with temporary password sent.');
        } catch (\Exception $e) {
            return redirect()->route('admin.onboarding.index')->with('error', 'Error approving request: '.$e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $onboarding = OnboardingRequest::findOrFail($id);

        $this->onboardingService->rejectRequest($id, $validated['reason']);

        Notification::route('mail', $onboarding->email)->notify(
            new OnboardingStatusNotification($onboarding->first_name.' '.$onboarding->last_name, 'rejected', $validated['reason'])
        );

        return redirect()->route('admin.onboarding.index')->with('success', 'Onboarding request rejected.');
    }

    public function requestInfo(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $onboarding = OnboardingRequest::findOrFail($id);

        $this->onboardingService->requestInfo($id, $validated['message']);

        Notification::route('mail', $onboarding->email)->notify(
            new OnboardingStatusNotification($onboarding->first_name.' '.$onboarding->last_name, 'info_requested', $validated['message'])
        );

        return redirect()->route('admin.onboarding.index')->with('success', 'Information requested from candidate.');
    }
}
