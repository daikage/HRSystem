<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * Public list of open positions visible to everyone.
     */
    public function index(Request $request): View
    {
        $query = JobListing::open()->withCount('applications');

        if ($department = $request->query('department')) {
            $query->where('department', $department);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('department', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            });
        }

        $jobs = $query->latest()->paginate(9)->withQueryString();
        $departments = JobListing::open()->select('department')->distinct()->orderBy('department')->pluck('department');

        return view('jobs.index', compact('jobs', 'departments'));
    }

    /**
     * Show a single job vacancy.
     */
    public function show(JobListing $job): View
    {
        return view('jobs.show', compact('job'));
    }
}