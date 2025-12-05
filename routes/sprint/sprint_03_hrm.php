<?php

use App\Http\Controllers\Employee\EmployeeSelfServiceController;
use App\Http\Controllers\HRM\DepartmentController;
use App\Http\Controllers\HRM\EmployeeController;
use App\Http\Controllers\HRM\PositionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 3: HRM MODULE
| - Departments (CRUD, Assign Manager)
| - Positions (CRUD)
| - Employees (CRUD, Export, Terminate)
| - Employee Self Service (All Authenticated Users)
|
| Access: Admin + Finance_HR (Operator = No Access)
| ESS Access: All Authenticated Users
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| HRM MAIN ROUTES (Admin + Finance_HR)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr', 'permission:employees.manage'])
    ->prefix('hrm')
    ->name('hrm.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | DEPARTMENTS MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
            Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
            Route::post('/{department}/assign-manager', [DepartmentController::class, 'assignManager'])->name('assign-manager');
        });
        
        /*
        |----------------------------------------------------------------------
        | POSITIONS MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::prefix('positions')->name('positions.')->group(function () {
            Route::get('/', [PositionController::class, 'index'])->name('index');
            Route::get('/create', [PositionController::class, 'create'])->name('create');
            Route::post('/', [PositionController::class, 'store'])->name('store');
            Route::get('/{position}', [PositionController::class, 'show'])->name('show');
            Route::get('/{position}/edit', [PositionController::class, 'edit'])->name('edit');
            Route::put('/{position}', [PositionController::class, 'update'])->name('update');
            Route::delete('/{position}', [PositionController::class, 'destroy'])->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | EMPLOYEES MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('index');
            Route::get('/create', [EmployeeController::class, 'create'])->name('create');
            Route::post('/', [EmployeeController::class, 'store'])->name('store');
            Route::get('/export', [EmployeeController::class, 'export'])->name('export');
            Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
            Route::post('/{employee}/terminate', [EmployeeController::class, 'terminate'])->name('terminate');
        });
    });

/*
|--------------------------------------------------------------------------
| EMPLOYEE SELF SERVICE (All Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeSelfServiceController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [EmployeeSelfServiceController::class, 'profile'])->name('profile');
    Route::put('/profile', [EmployeeSelfServiceController::class, 'updateProfile'])->name('profile.update');
    Route::get('/attendance', [EmployeeSelfServiceController::class, 'attendance'])->name('attendance');
    Route::get('/leaves', [EmployeeSelfServiceController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/request', [EmployeeSelfServiceController::class, 'requestLeave'])->name('leaves.request');
    Route::get('/payslips', [EmployeeSelfServiceController::class, 'payslips'])->name('payslips');
});