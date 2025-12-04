<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 1: USER MANAGEMENT
| - Users (CRUD, Reset Password, Toggle Status)
| - Roles (CRUD)
| - Permissions (CRUD)
| - Activity Logs (View, Clear, Export)
| - Audit Logs (View, Clear, Export, Statistics)
|
| Access: Admin ONLY (Operator & Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | USERS MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::middleware('permission:users.manage')
            ->prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/{user}', [UserController::class, 'show'])->name('show');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
                Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
                Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            });
        
        /*
        |----------------------------------------------------------------------
        | ROLES MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::middleware('permission:roles.manage')
            ->prefix('roles')
            ->name('roles.')
            ->group(function () {
                Route::get('/', [RoleController::class, 'index'])->name('index');
                Route::get('/create', [RoleController::class, 'create'])->name('create');
                Route::post('/', [RoleController::class, 'store'])->name('store');
                Route::get('/{role}', [RoleController::class, 'show'])->name('show');
                Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
                Route::put('/{role}', [RoleController::class, 'update'])->name('update');
                Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
            });
        
        /*
        |----------------------------------------------------------------------
        | PERMISSIONS MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::middleware('permission:permissions.manage')
            ->prefix('permissions')
            ->name('permissions.')
            ->group(function () {
                Route::get('/', [PermissionController::class, 'index'])->name('index');
                Route::get('/create', [PermissionController::class, 'create'])->name('create');
                Route::post('/', [PermissionController::class, 'store'])->name('store');
                Route::get('/{permission}', [PermissionController::class, 'show'])->name('show');
                Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
                Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
                Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
            });
        
        /*
        |----------------------------------------------------------------------
        | LOGS MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::prefix('logs')->name('logs.')->group(function () {
            
            // Activity Logs
            Route::middleware('permission:activity_logs.view')
                ->prefix('activity')
                ->name('activity.')
                ->group(function () {
                    Route::get('/', [ActivityLogController::class, 'index'])->name('index');
                    Route::post('/clear', [ActivityLogController::class, 'clear'])->name('clear');
                    Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
                });
            
            // Audit Logs
            Route::middleware('permission:audit_logs.view')
                ->prefix('audit')
                ->name('audit.')
                ->group(function () {
                    Route::get('/', [AuditLogController::class, 'index'])->name('index');
                    Route::get('/statistics', [AuditLogController::class, 'statistics'])->name('statistics');
                    Route::get('/export', [AuditLogController::class, 'export'])->name('export');
                    Route::get('/{auditLog}', [AuditLogController::class, 'show'])->name('show');
                    Route::post('/clear', [AuditLogController::class, 'clear'])->name('clear');
                });
        });
    });