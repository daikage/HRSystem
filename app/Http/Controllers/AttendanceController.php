<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $todayRecord = AttendanceRecord::where('user_id', Auth::id())
                                        ->where('date', $today)
                                        ->first();

        if (Auth::user()->hasRole('admin')) {
            $query = AttendanceRecord::with('user');

            if ($search = $request->query('search')) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            }

            if ($date = $request->query('date')) {
                $query->whereDate('date', $date);
            }

            $attendanceRecords = $query->orderBy('date', 'desc')->orderBy('clock_in', 'desc')->paginate(10)->withQueryString();
        } else {
            $attendanceRecords = AttendanceRecord::where('user_id', Auth::id())->orderBy('date', 'desc')->paginate(10);
        }

        return view('attendance.index', compact('attendanceRecords', 'todayRecord'));
    }

    public function clockIn()
    {
        $today = Carbon::today()->toDateString();

        // firstOrCreate relies on the DB unique (user_id, date) constraint, so
        // concurrent/double submissions are safe and never create duplicates.
        $record = AttendanceRecord::firstOrCreate(
            ['user_id' => Auth::id(), 'date' => $today],
            ['clock_in' => Carbon::now()->toTimeString()]
        );

        if (! $record->wasRecentlyCreated) {
            return redirect()->back()->with('error', 'You have already clocked in today.');
        }

        return redirect()->back()->with('success', 'Clocked in successfully at '.Carbon::now()->format('H:i'));
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
public function export(Request $request): StreamedResponse
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $query = AttendanceRecord::with('user');

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($date = $request->query('date')) {
            $query->whereDate('date', $date);
        }

        $records = $query->orderBy('date', 'desc')->get();

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee', 'Email', 'Date', 'Clock In', 'Clock Out', 'Hours']);

            foreach ($records as $record) {
                $hours = ($record->clock_in && $record->clock_out)
                    ? number_format(Carbon::parse($record->clock_in)->diffInMinutes(Carbon::parse($record->clock_out)) / 60, 2)
                    : '';

                fputcsv($out, [
                    $record->user->name ?? '',
                    $record->user->email ?? '',
                    $record->date,
                    $record->clock_in,
                    $record->clock_out,
                    $hours,
                ]);
            }

            fclose($out);
        }, 'attendance-'.now()->format('Y-m-d').'.csv');
    }
}
