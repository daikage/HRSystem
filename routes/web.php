<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // HR Modules
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    
    Route::resource('leave-requests', \App\Http\Controllers\LeaveRequestController::class)->except(['show', 'edit', 'update']);
    Route::patch('leave-requests/{leave_request}/status', [\App\Http\Controllers\LeaveRequestController::class, 'updateStatus'])->name('leave-requests.update-status');
    
    Route::resource('attendance', \App\Http\Controllers\AttendanceController::class)->only(['index']);
    Route::post('attendance/clock-in', [\App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('attendance/clock-out', [\App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    
    Route::resource('payroll', \App\Http\Controllers\PayrollController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('payroll/{payroll}/mark-paid', [\App\Http\Controllers\PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
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
});
