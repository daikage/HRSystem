<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Notifications\JobApplicationStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class JobApplicationReviewController extends Controller
{
    /**
     * Applications inbox, grouped under the recruitment/onboarding area.
     */
    public function index(Request $request): View
    {
        $query = JobApplication::with('job');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(10)->withQueryString();

        return view('admin.job-applications.index', compact('applications'));
    }

    public function approve(JobApplication $application): RedirectResponse
    {
        $application->update(['status' => 'approved']);

        Notification::route('mail', $application->email)->notify(
            new JobApplicationStatusNotification($application->job->title, 'approved')
        );

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Application approved and the applicant has been notified.');
    }

    public function reject(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        $application->update([
            'status' => 'rejected',
            'admin_feedback' => $validated['feedback'],
        ]);

        Notification::route('mail', $application->email)->notify(
            new JobApplicationStatusNotification($application->job->title, 'rejected', $validated['feedback'])
        );

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Application marked as rejected and the applicant has been notified.');
    }
}