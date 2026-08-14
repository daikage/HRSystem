<?php

namespace App\Http\Controllers;

use App\Models\PayrollRecord;
use App\Models\User;
use App\Notifications\PayrollPaidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            $query = PayrollRecord::with('user');

            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }

            if ($search = $request->query('search')) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            }

            $payrollRecords = $query->orderBy('pay_period_end', 'desc')->paginate(10)->withQueryString();
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

        // Prevent generating a second record that overlaps an existing pay period
        // for the same employee.
        $periodExists = PayrollRecord::where('user_id', $validated['user_id'])
            ->whereDate('pay_period_start', '<=', $validated['pay_period_end'])
            ->whereDate('pay_period_end', '>=', $validated['pay_period_start'])
            ->exists();

        if ($periodExists) {
            return back()->with('error', 'A payroll record already exists for this employee in an overlapping pay period.')->withInput();
        }

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

        // Notify the employee their payslip is available.
        Notification::send(
            $payroll->user,
            new PayrollPaidNotification(
                $payroll->pay_period_start.' to '.$payroll->pay_period_end,
                number_format($payroll->net_pay, 2)
            )
        );

        return redirect()->back()->with('success', 'Payroll record marked as paid.');
    }
public function export(Request $request): StreamedResponse
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $query = PayrollRecord::with('user');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $records = $query->orderBy('pay_period_end', 'desc')->get();

        return response()->streamDownload(function () use ($records) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee', 'Email', 'Pay Period Start', 'Pay Period End', 'Base Salary', 'Bonuses', 'Deductions', 'Net Pay', 'Status']);

            foreach ($records as $record) {
                fputcsv($out, [
                    $record->user->name ?? '',
                    $record->user->email ?? '',
                    $record->pay_period_start,
                    $record->pay_period_end,
                    $record->base_salary,
                    $record->bonuses,
                    $record->deductions,
                    $record->net_pay,
                    $record->status,
                ]);
            }

            fclose($out);
        }, 'payroll-'.now()->format('Y-m-d').'.csv');
    }
}
