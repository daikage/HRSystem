<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobListingController extends Controller
{
    public function index(Request $request): View
    {
        $jobs = JobListing::withCount('applications')
            ->with('creator')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateJob($request);

        JobListing::create($validated + ['created_by' => Auth::id()]);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job listing published successfully.');
    }

    public function edit(JobListing $job): View
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobListing $job): RedirectResponse
    {
        $validated = $this->validateJob($request);

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job listing updated successfully.');
    }

    public function updateStatus(JobListing $job): RedirectResponse
    {
        $job->update([
            'status' => $job->isOpen() ? 'closed' : 'open',
        ]);

        return redirect()->route('admin.jobs.index')
            ->with('success', $job->isOpen() ? 'Job listing reopened.' : 'Job listing closed.');
    }

    public function destroy(JobListing $job): RedirectResponse
    {
        // Deleting a listing also deletes its applications (cascade).
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job listing removed.');
    }

    private function validateJob(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|string|in:Full-time,Part-time,Contract,Internship',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'description' => 'required|string|max:10000',
            'requirements' => 'nullable|string|max:10000',
            'status' => 'sometimes|in:open,closed',
        ]);
    }
}