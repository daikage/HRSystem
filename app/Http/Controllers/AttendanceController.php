<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $todayRecord = AttendanceRecord::where('user_id', Auth::id())
                                        ->where('date', $today)
                                        ->first();

        if (Auth::user()->hasRole('admin')) {
            $attendanceRecords = AttendanceRecord::with('user')->orderBy('date', 'desc')->orderBy('clock_in', 'desc')->paginate(10);
        } else {
            $attendanceRecords = AttendanceRecord::where('user_id', Auth::id())->orderBy('date', 'desc')->paginate(10);
        }

        return view('attendance.index', compact('attendanceRecords', 'todayRecord'));
    }

    public function clockIn()
    {
        $today = Carbon::today()->toDateString();
        
        $existingRecord = AttendanceRecord::where('user_id', Auth::id())
                                          ->where('date', $today)
                                          ->first();

        if ($existingRecord) {
            return redirect()->back()->with('error', 'You have already clocked in today.');
        }

        AttendanceRecord::create([
            'user_id' => Auth::id(),
            'date' => $today,
            'clock_in' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Clocked in successfully at ' . Carbon::now()->format('H:i'));
    }

    public function clockOut()
    {
        $today = Carbon::today()->toDateString();
        
        $existingRecord = AttendanceRecord::where('user_id', Auth::id())
                                          ->where('date', $today)
                                          ->first();

        if (!$existingRecord) {
            return redirect()->back()->with('error', 'You must clock in first before clocking out.');
        }

        if ($existingRecord->clock_out) {
            return redirect()->back()->with('error', 'You have already clocked out today.');
        }

        $existingRecord->update([
            'clock_out' => Carbon::now()->toTimeString(),
        ]);

        return redirect()->back()->with('success', 'Clocked out successfully at ' . Carbon::now()->format('H:i'));
    }
}
