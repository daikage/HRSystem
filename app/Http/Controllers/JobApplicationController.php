<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use App\Notifications\NewJobReceivedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    /**
     * Show the application form for a job.
     */
    public function create(JobListing $job): View
    {
        if (! $job->isOpen()) {
            abort(404, 'This position is no longer accepting applications.');
        }

        return view('jobs.apply', compact('job'));
    }

    /**
     * Store a new job application.
     */
    public function store(Request $request, JobListing $job): RedirectResponse
    {
        if (! $job->isOpen()) {
            abort(404, 'This position is no longer accepting applications.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'nullable|string|max:30',
            'resume_link' => 'nullable|url|max:2048',
            'cover_letter' => 'nullable|string|max:4000',
        ]);

        $application = JobApplication::create([
            'job_listing_id' => $job->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'resume_link' => $validated['resume_link'] ?? null,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'pending',
        ]);

        // Alert every admin that a new application has arrived so it can be
        // reviewed from the onboarding / recruitment area in the backend.
        Notification::send(
            User::role('admin')->get(),
            new NewJobReceivedNotification(
                $application->full_name,
                $job->title,
                $application->email
            )
        );

        return redirect()->route('jobs.applied')
            ->with('success', 'Your application has been received.');
    }

    /**
     * Confirmation screen shown after a successful application.
     */
    public function success(Request $request): View
    {
        return view('jobs.applied');
    }
}