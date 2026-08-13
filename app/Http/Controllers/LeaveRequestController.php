<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Notifications\LeaveRequestStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $query = LeaveRequest::with('user');
        } else {
            $query = LeaveRequest::where('user_id', $user->id);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Leave allowance is only meaningful for non-admin employees.
        $allowance = $user->hasRole('admin') ? null : $this->computeAllowance($user);

        return view('leave-requests.index', compact('leaveRequests', 'allowance'));
    }

    public function create()
    {
        $user = Auth::user();
        $allowance = $user->hasRole('admin') ? null : $this->computeAllowance($user);
        return view('leave-requests.create', compact('allowance'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Annual,Sick,Unpaid,Other',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Block overlapping requests (pending or approved) for the same user.
        $overlapExists = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '<=', $validated['end_date'])
            ->whereDate('end_date', '>=', $validated['start_date'])
            ->exists();

        if ($overlapExists) {
            return back()->with('error', 'You already have a pending or approved leave request that overlaps these dates.')->withInput();
        }

        // Enforce the annual leave balance when requesting annual leave.
        $requestedDays = \Illuminate\Support\Carbon::parse($validated['start_date'])
            ->diffInDays(\Illuminate\Support\Carbon::parse($validated['end_date'])) + 1;

        if ($user->hasRole('employee') && $validated['type'] === 'Annual') {
            $allowance = $this->computeAllowance($user);
            if ($requestedDays > $allowance['remaining']) {
                return back()->with('error', 'This request exceeds your remaining annual leave balance ('.$allowance['remaining'].' day(s) remaining).')->withInput();
            }
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Notify all admins about the new request.
        $requestingUser = $user;
        Notification::send(
            User::role('admin')->get(),
            new \App\Notifications\NewLeaveRequestNotification(
                $requestingUser->name,
                $validated['type'],
                $validated['start_date'].' to '.$validated['end_date']
            )
        );

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

        // Notify the requesting user about the decision.
        Notification::send(
            $leaveRequest->user,
            new LeaveRequestStatusNotification(
                $validated['status'],
                $leaveRequest->type,
                $leaveRequest->start_date.' to '.$leaveRequest->end_date
            )
        );

        return redirect()->back()->with('success', 'Leave request status updated to '.$validated['status'].'.');
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
public function export(Request $request): StreamedResponse
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $query = LeaveRequest::with('user');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->get();

        return response()->streamDownload(function () use ($leaveRequests) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee', 'Email', 'Type', 'Start Date', 'End Date', 'Days', 'Status', 'Reason']);

            foreach ($leaveRequests as $request) {
                fputcsv($out, [
                    $request->user->name ?? '',
                    $request->user->email ?? '',
                    $request->type,
                    $request->start_date,
                    $request->end_date,
                    $request->duration,
                    $request->status,
                    $request->reason,
                ]);
            }

            fclose($out);
        }, 'leave-requests-'.now()->format('Y-m-d').'.csv');
    }

    /**
     * Compute the employee's annual leave allowance for the current year.
     *
     * @return array{entitlement: int, used: int, remaining: int}
     */
    private function computeAllowance(User $user): array
    {
        $entitlement = $user->employeeProfile->annual_leave_entitlement ?? 20;

        $used = LeaveRequest::where('user_id', $user->id)
            ->where('type', 'Annual')
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '>=', now()->startOfYear())
            ->get()
            ->sum(fn ($leave) => $leave->duration);

        return [
            'entitlement' => (int) $entitlement,
            'used' => (int) $used,
            'remaining' => (int) max(0, $entitlement - $used),
        ];
    }
}
