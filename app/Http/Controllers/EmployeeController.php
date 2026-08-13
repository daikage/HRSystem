<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProfile;
use App\Models\User;
use App\Notifications\AccountWelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeProfile::query()->with('user');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })
                ->orWhere('department', 'like', '%'.$search.'%')
                ->orWhere('job_title', 'like', '%'.$search.'%');
            });
        }

        $employees = $query->latest()->paginate(10)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'department' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'joining_date' => 'required|date',
            'annual_leave_entitlement' => 'nullable|integer|min:0|max:365',
        ]);

        $generatedPassword = Str::random(12);

        DB::beginTransaction();
        try {
            // Create user and flag it so they are forced to change the password
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($generatedPassword),
                'must_change_password' => true,
            ]);

            // Assign employee role
            $user->assignRole('employee');

            // Create profile
            $user->employeeProfile()->create([
                'department' => $validated['department'],
                'job_title' => $validated['job_title'],
                'salary' => $validated['salary'],
                'joining_date' => $validated['joining_date'],
                'annual_leave_entitlement' => $validated['annual_leave_entitlement'] ?? 20,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to create employee: '.$e->getMessage())->withInput();
        }

        // Send the temporary password securely instead of exposing it on screen.
        Notification::route('mail', $user->email)->notify(
            new AccountWelcomeNotification($generatedPassword)
        );

        return redirect()->route('employees.index')->with('success', 'Employee created successfully. A welcome email with a temporary password has been sent.');
    }

    public function show(EmployeeProfile $employee)
    {
        $employee->load('user');
        return view('employees.show', compact('employee'));
    }

    public function edit(EmployeeProfile $employee)
    {
        $employee->load('user');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, EmployeeProfile $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$employee->user_id,
            'department' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'joining_date' => 'required|date',
            'annual_leave_entitlement' => 'nullable|integer|min:0|max:365',
        ]);

        DB::beginTransaction();
        try {
            // Update user
            $employee->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update profile
            $employee->update([
                'department' => $validated['department'],
                'job_title' => $validated['job_title'],
                'salary' => $validated['salary'],
                'joining_date' => $validated['joining_date'],
                'annual_leave_entitlement' => $validated['annual_leave_entitlement'] ?? $employee->annual_leave_entitlement,
            ]);

            DB::commit();

            return redirect()->route('employees.show', $employee)->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update employee: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(EmployeeProfile $employee)
    {
        // Deleting the user will cascade and delete the employee profile
        $employee->user->delete();
        
        return redirect()->route('employees.index')->with('success', 'Employee removed successfully.');
    }
public function export(Request $request): StreamedResponse
    {
        $query = EmployeeProfile::query()->with('user');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                })
                ->orWhere('department', 'like', '%'.$search.'%')
                ->orWhere('job_title', 'like', '%'.$search.'%');
            });
        }

        $employees = $query->get();

        return response()->streamDownload(function () use ($employees) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Department', 'Job Title', 'Salary', 'Joining Date', 'Annual Leave']);

            foreach ($employees as $employee) {
                fputcsv($out, [
                    $employee->user->name ?? '',
                    $employee->user->email ?? '',
                    $employee->department ?? '',
                    $employee->job_title ?? '',
                    $employee->salary,
                    $employee->joining_date,
                    $employee->annual_leave_entitlement,
                ]);
            }

            fclose($out);
        }, 'employees-'.now()->format('Y-m-d').'.csv');
    }
}
