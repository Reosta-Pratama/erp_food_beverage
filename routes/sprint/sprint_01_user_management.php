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
        | USERS
        |----------------------------------------------------------------------
        */
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->middleware('permission:users.manage')
                ->name('index');
            Route::get('/create', [UserController::class, 'create'])
                ->middleware('permission:users.manage')
                ->name('create');
            Route::post('/', [UserController::class, 'store'])
                ->middleware('permission:users.manage')
                ->name('store');
            Route::get('/{user}', [UserController::class, 'show'])
                ->middleware('permission:users.manage')
                ->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])
                ->middleware('permission:users.manage')
                ->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])
                ->middleware('permission:users.manage')
                ->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->middleware('permission:users.manage')
                ->name('destroy');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
                ->middleware('permission:users.manage')
                ->name('reset-password');
            Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                ->middleware('permission:users.manage')
                ->name('toggle-status');
        });
        
        /*
        |----------------------------------------------------------------------
        | ROLES
        |----------------------------------------------------------------------
        */
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])
                ->middleware('permission:roles.manage')
                ->name('index');
            Route::get('/create', [RoleController::class, 'create'])
                ->middleware('permission:roles.manage')
                ->name('create');
            Route::post('/', [RoleController::class, 'store'])
                ->middleware('permission:roles.manage')
                ->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])
                ->middleware('permission:roles.manage')
                ->name('show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])
                ->middleware('permission:roles.manage')
                ->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])
                ->middleware('permission:roles.manage')
                ->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])
                ->middleware('permission:roles.manage')
                ->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | PERMISSIONS
        |----------------------------------------------------------------------
        */
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])
                ->middleware('permission:permissions.manage')
                ->name('index');
            Route::get('/create', [PermissionController::class, 'create'])
                ->middleware('permission:permissions.manage')
                ->name('create');
            Route::post('/', [PermissionController::class, 'store'])
                ->middleware('permission:permissions.manage')
                ->name('store');
            Route::get('/{permission}', [PermissionController::class, 'show'])
                ->middleware('permission:permissions.manage')
                ->name('show');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
                ->middleware('permission:permissions.manage')
                ->name('edit');
            Route::put('/{permission}', [PermissionController::class, 'update'])
                ->middleware('permission:permissions.manage')
                ->name('update');
            Route::delete('/{permission}', [PermissionController::class, 'destroy'])
                ->middleware('permission:permissions.manage')
                ->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | LOGS MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::prefix('logs')->name('logs.')->group(function () {
            
            // Activity Logs
            Route::prefix('activity')->name('activity.')->group(function () {
                Route::get('/', [ActivityLogController::class, 'index'])
                    ->middleware('permission:activity_logs.view')
                    ->name('index');
                Route::post('/clear', [ActivityLogController::class, 'clear'])
                    ->middleware('permission:activity_logs.view')
                    ->name('clear');
                Route::get('/export', [ActivityLogController::class, 'export'])
                    ->middleware('permission:activity_logs.view')
                    ->name('export');
            });
            
            // Audit Logs
            Route::prefix('audit')->name('audit.')->group(function () {
                Route::get('/', [AuditLogController::class, 'index'])
                    ->middleware('permission:audit_logs.view')
                    ->name('index');
                Route::get('/statistics', [AuditLogController::class, 'statistics'])
                    ->middleware('permission:audit_logs.view')
                    ->name('statistics');
                Route::get('/export', [AuditLogController::class, 'export'])
                    ->middleware('permission:audit_logs.view')
                    ->name('export');
                Route::get('/{auditLog}', [AuditLogController::class, 'show'])
                    ->middleware('permission:audit_logs.view')
                    ->name('show');
                Route::post('/clear', [AuditLogController::class, 'clear'])
                    ->middleware('permission:audit_logs.view')
                    ->name('clear');
            });
        });
    });