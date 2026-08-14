<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'password.changed'])->name('dashboard');

Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // HR Modules
    // Employee directory (index/show) is viewable by all authenticated users,
    // but only admins may create/edit/delete employees. The admin-gated routes
    // are declared FIRST so that the literal "create" route is matched before
    // the parameterised "show" route registered in the public resource below.
    Route::middleware('role:admin')->group(function () {
        Route::get('employees/export', [\App\Http\Controllers\EmployeeController::class, 'export'])->name('employees.export');
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->only(['index', 'show']);

    Route::get('leave-requests/export', [\App\Http\Controllers\LeaveRequestController::class, 'export'])->name('leave-requests.export');
    Route::resource('leave-requests', \App\Http\Controllers\LeaveRequestController::class)->except(['show', 'edit', 'update']);
    Route::patch('leave-requests/{leave_request}/status', [\App\Http\Controllers\LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update-status');

    Route::resource('attendance', \App\Http\Controllers\AttendanceController::class)->only(['index']);
    Route::get('attendance/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');
    Route::post('attendance/clock-in', [\App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('attendance/clock-out', [\App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

    Route::get('payroll/export', [\App\Http\Controllers\PayrollController::class, 'export'])->name('payroll.export');
    Route::resource('payroll', \App\Http\Controllers\PayrollController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('payroll/{payroll}/mark-paid', [\App\Http\Controllers\PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
});

// Forced password change for accounts created with a temporary password.
Route::middleware('auth')->group(function () {
    Route::get('set-password', [\App\Http\Controllers\Auth\ForcedPasswordController::class, 'edit'])->name('forced-password.edit');
    Route::put('set-password', [\App\Http\Controllers\Auth\ForcedPasswordController::class, 'update'])->name('forced-password.update');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\OnboardingReviewController;

// Public Onboarding Routes
Route::get('/onboarding', [OnboardingController::class, 'create'])->name('onboarding.create');
Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
Route::get('/onboarding/success', [OnboardingController::class, 'success'])->name('onboarding.success');

// Admin Onboarding Review Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin/onboarding')->name('admin.onboarding.')->group(function () {
    Route::get('/', [OnboardingReviewController::class, 'index'])->name('index');
    Route::post('/{id}/approve', [OnboardingReviewController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [OnboardingReviewController::class, 'reject'])->name('reject');
    Route::post('/{id}/request-info', [OnboardingReviewController::class, 'requestInfo'])->name('request-info');
});
