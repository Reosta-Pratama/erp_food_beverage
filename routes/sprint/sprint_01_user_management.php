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
| Access: Admin Only
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
                ->middleware('permission:users.read')
                ->name('index');
            Route::get('/create', [UserController::class, 'create'])
                ->middleware('permission:users.create')
                ->name('create');
            Route::post('/', [UserController::class, 'store'])
                ->middleware('permission:users.create')
                ->name('store');
            Route::get('/{user}', [UserController::class, 'show'])
                ->middleware('permission:users.read')
                ->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])
                ->middleware('permission:users.update')
                ->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])
                ->middleware('permission:users.update')
                ->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->middleware('permission:users.delete')
                ->name('destroy');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
                ->middleware('permission:users.update')
                ->name('reset-password');
            Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                ->middleware('permission:users.update')
                ->name('toggle-status');
        });
        
        /*
        |----------------------------------------------------------------------
        | ROLES
        |----------------------------------------------------------------------
        */
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])
                ->middleware('permission:roles.read')
                ->name('index');
            Route::get('/create', [RoleController::class, 'create'])
                ->middleware('permission:roles.create')
                ->name('create');
            Route::post('/', [RoleController::class, 'store'])
                ->middleware('permission:roles.create')
                ->name('store');
            Route::get('/{role}', [RoleController::class, 'show'])
                ->middleware('permission:roles.read')
                ->name('show');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])
                ->middleware('permission:roles.update')
                ->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])
                ->middleware('permission:roles.update')
                ->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])
                ->middleware('permission:roles.delete')
                ->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | PERMISSIONS
        |----------------------------------------------------------------------
        */
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])
                ->middleware('permission:permissions.read')
                ->name('index');
            Route::get('/create', [PermissionController::class, 'create'])
                ->middleware('permission:permissions.create')
                ->name('create');
            Route::post('/', [PermissionController::class, 'store'])
                ->middleware('permission:permissions.create')
                ->name('store');
            Route::get('/{permission}', [PermissionController::class, 'show'])
                ->middleware('permission:permissions.read')
                ->name('show');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
                ->middleware('permission:permissions.update')
                ->name('edit');
            Route::put('/{permission}', [PermissionController::class, 'update'])
                ->middleware('permission:permissions.update')
                ->name('update');
            Route::delete('/{permission}', [PermissionController::class, 'destroy'])
                ->middleware('permission:permissions.delete')
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
                    ->middleware('permission:logs.read')
                    ->name('index');
                Route::post('/clear', [ActivityLogController::class, 'clear'])
                    ->middleware('permission:logs.delete')
                    ->name('clear');
                Route::get('/export', [ActivityLogController::class, 'export'])
                    ->middleware('permission:logs.read')
                    ->name('export');
            });
            
            // Audit Logs
            Route::prefix('audit')->name('audit.')->group(function () {
                Route::get('/', [AuditLogController::class, 'index'])
                    ->middleware('permission:logs.read')
                    ->name('index');
                Route::get('/statistics', [AuditLogController::class, 'statistics'])
                    ->middleware('permission:logs.read')
                    ->name('statistics');
                Route::get('/export', [AuditLogController::class, 'export'])
                    ->middleware('permission:logs.read')
                    ->name('export');
                Route::get('/{auditLog}', [AuditLogController::class, 'show'])
                    ->middleware('permission:logs.read')
                    ->name('show');
                Route::post('/clear', [AuditLogController::class, 'clear'])
                    ->middleware('permission:logs.delete')
                    ->name('clear');
            });
        });
    });