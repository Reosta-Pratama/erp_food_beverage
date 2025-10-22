<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('showLogin');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // General dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
            
            Route::resource('users', UserController::class);
            Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Operator routes
    Route::middleware('role:operator')
        ->prefix('operator')
        ->name('operator.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('operator.dashboard');
            })->name('dashboard');
    });

    // Finance/HR routes
    Route::middleware('role:finance_hr')
        ->prefix('finance-hr')
        ->name('finance_hr.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('finance_hr.dashboard');
            })->name('dashboard');
    });
});


// Route::get('/', function () {
//     return view('welcome');
// });
