<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $payrollRecords = PayrollRecord::with('user')->orderBy('pay_period_end', 'desc')->paginate(10);
        } else {
            $payrollRecords = PayrollRecord::where('user_id', Auth::id())->orderBy('pay_period_end', 'desc')->paginate(10);
        }
        
        return view('payroll.index', compact('payrollRecords'));
    }

    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Only get users who have an employee profile
        $employees = User::role('employee')->with('employeeProfile')->get();
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after_or_equal:pay_period_start',
            'base_salary' => 'required|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $bonuses = $validated['bonuses'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $netPay = $validated['base_salary'] + $bonuses - $deductions;

        PayrollRecord::create([
            'user_id' => $validated['user_id'],
            'pay_period_start' => $validated['pay_period_start'],
            'pay_period_end' => $validated['pay_period_end'],
            'base_salary' => $validated['base_salary'],
            'bonuses' => $bonuses,
            'deductions' => $deductions,
            'net_pay' => $netPay,
            'status' => 'pending',
        ]);

        return redirect()->route('payroll.index')->with('success', 'Payroll record generated successfully.');
    }

    public function show(PayrollRecord $payroll)
    {
        if (!Auth::user()->hasRole('admin') && $payroll->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $payroll->load(['user', 'user.employeeProfile']);
        return view('payroll.show', compact('payroll'));
    }

    public function markPaid(Request $request, PayrollRecord $payroll)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $payroll->update([
            'status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Payroll record marked as paid.');
    }
}
