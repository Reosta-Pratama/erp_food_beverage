<?php
use App\Http\Controllers\admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Admin Dashboard
Route::middleware('role:admin')
    ->get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->name('admin.dashboard');

// Operator Dashboard
Route::middleware('role:operator')
    ->get('/operator/dashboard', [DashboardController::class, 'operator'])
    ->name('operator.dashboard');

// Finance/HR Dashboard
Route::middleware('role:finance_hr')
    ->get('/finance-hr/dashboard', [DashboardController::class, 'financeHr'])
    ->name('finance_hr.dashboard');

