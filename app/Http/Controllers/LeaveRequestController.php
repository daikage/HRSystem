<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $leaveRequests = LeaveRequest::with('user')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $leaveRequests = LeaveRequest::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        }
        
        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Annual,Sick,Unpaid,Other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted successfully.');
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leaveRequest->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Leave request status updated to ' . $validated['status'] . '.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        // Users can only delete their own pending requests, admins can delete any
        if (!Auth::user()->hasRole('admin') && ($leaveRequest->user_id !== Auth::id() || $leaveRequest->status !== 'pending')) {
            abort(403, 'Unauthorized action.');
        }

        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Leave request cancelled.');
    }
}
