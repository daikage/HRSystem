<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\AttendanceRecord;
use App\Models\PayrollRecord;
use App\Models\OnboardingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        return $this->employeeDashboard($user);
    }

    private function adminDashboard()
    {
        $totalEmployees = User::role('employee')->count();
        
        $today = Carbon::today()->toDateString();
        $onLeaveToday = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();
            
        $pendingOnboarding = OnboardingRequest::where('status', 'pending')->count();
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyPayroll = PayrollRecord::whereMonth('pay_period_start', $currentMonth)
            ->whereYear('pay_period_start', $currentYear)
            ->sum('net_pay');
            
        $recentHires = User::role('employee')
            ->with('employeeProfile')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'totalEmployees',
            'onLeaveToday',
            'pendingOnboarding',
            'monthlyPayroll',
            'recentHires'
        ));
    }

    private function employeeDashboard($user)
    {
        $today = Carbon::today()->toDateString();
        
        $attendanceToday = AttendanceRecord::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
            
        $nextLeave = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '>', $today)
            ->orderBy('start_date', 'asc')
            ->first();
            
        $latestPayslip = PayrollRecord::where('user_id', $user->id)
            ->orderBy('pay_period_end', 'desc')
            ->first();

        return view('dashboard', compact(
            'attendanceToday',
            'nextLeave',
            'latestPayslip'
        ));
    }
}
