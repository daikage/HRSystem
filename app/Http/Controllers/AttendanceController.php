<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendanceRecords = AttendanceRecord::paginate(10);
        return view('attendance.index', compact('attendanceRecords'));
    }
}
