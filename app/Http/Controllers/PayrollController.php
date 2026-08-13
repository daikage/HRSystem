<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrollRecords = PayrollRecord::paginate(10);
        return view('payroll.index', compact('payrollRecords'));
    }
}
