<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = EmployeeProfile::with('user')->paginate(10);
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
        ]);

        $generatedPassword = Str::random(12);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($generatedPassword),
            ]);
            
            // Assign employee role
            $user->assignRole('employee');

            // Create profile
            $user->employeeProfile()->create([
                'department' => $validated['department'],
                'job_title' => $validated['job_title'],
                'salary' => $validated['salary'],
                'joining_date' => $validated['joining_date'],
            ]);

            DB::commit();

            return redirect()->route('employees.index')->with('success', 'Employee created successfully. Temporary Password: ' . $generatedPassword);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create employee: ' . $e->getMessage())->withInput();
        }
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
            'department' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'joining_date' => 'required|date',
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
}
