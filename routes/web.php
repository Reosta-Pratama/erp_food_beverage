<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\EmployeeSelfServiceController;
use App\Http\Controllers\HRM\DepartmentController;
use App\Http\Controllers\HRM\EmployeeController;
use App\Http\Controllers\HRM\PositionController;
use App\Http\Controllers\Inventory\BOMController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\LotController;
use App\Http\Controllers\Inventory\RecipeController;
use App\Http\Controllers\Inventory\StockMovementController;
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Inventory\WarehouseLocationController;
use App\Http\Controllers\Production\BatchController;
use App\Http\Controllers\Production\ProductionPlanningController;
use App\Http\Controllers\Production\WorkOrderController;
use App\Http\Controllers\Products\ProductCategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Settings\CompanyProfileController;
use App\Http\Controllers\Settings\CurrencyController;
use App\Http\Controllers\Settings\TaxRateController;
use App\Http\Controllers\Settings\UnitOfMeasureController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 1
| Guest Routes (Public)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login.show');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Auto redirect to role-based dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (Full Access)
    |--------------------------------------------------------------------------
    */  
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            
            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
            
            /*
            |----------------------------------------------------------------------
            | SPRINT 1
            | USER MANAGEMENT (Admin Only)
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
            | SPRINT 1
            | ROLE MANAGEMENT (Admin Only)
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
            | SPRINT 1
            | PERMISSION MANAGEMENT (Admin Only)
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
            | SPRINT 1
            | LOGS MANAGEMENT (Admin Only)
            |----------------------------------------------------------------------
            */
            Route::prefix('logs')->name('logs.')->group(function () {
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
                
                Route::prefix('audit')->name('audit.')->group(function () {
                    Route::get('/', [AuditLogController::class, 'index'])
                        ->middleware('permission:logs.read')
                        ->name('index');
                    // Route::get('/trail', [AuditLogController::class, 'trail'])
                    //     ->middleware('permission:logs.read')
                    //     ->name('trail');
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

            /*
            |----------------------------------------------------------------------
            | SPTRINT 2
            | SETTINGS MODULE (Admin Only)
            |----------------------------------------------------------------------
            */
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::prefix('company-profile')->name('company-profile.')->group(function () {
                    Route::get('/', [CompanyProfileController::class, 'index'])
                        ->middleware('permission:settings.read')
                        ->name('index');
                    Route::get('/edit', [CompanyProfileController::class, 'edit'])
                        ->middleware('permission:settings.update')
                        ->name('edit');
                    Route::put('/', [CompanyProfileController::class, 'update'])
                        ->middleware('permission:settings.update')
                        ->name('update');
                    Route::delete('/logo', [CompanyProfileController::class, 'deleteLogo'])
                        ->middleware('permission:settings.delete')
                        ->name('delete-logo');
                });
                
                Route::prefix('uom')->name('uom.')->group(function () {
                    Route::get('/', [UnitOfMeasureController::class, 'index'])
                        ->middleware('permission:settings.read')
                        ->name('index');
                    Route::get('/create', [UnitOfMeasureController::class, 'create'])
                        ->middleware('permission:settings.create')
                        ->name('create');
                    Route::post('/', [UnitOfMeasureController::class, 'store'])
                        ->middleware('permission:settings.create')
                        ->name('store');
                    Route::get('/{uom}', [UnitOfMeasureController::class, 'show'])
                        ->middleware('permission:settings.read')
                        ->name('show');
                    Route::get('/{uom}/edit', [UnitOfMeasureController::class, 'edit'])
                        ->middleware('permission:settings.update')
                        ->name('edit');
                    Route::put('/{uom}', [UnitOfMeasureController::class, 'update'])
                        ->middleware('permission:settings.update')
                        ->name('update');
                    Route::delete('/{uom}', [UnitOfMeasureController::class, 'destroy'])
                        ->middleware('permission:settings.delete')
                        ->name('destroy');
                });
                
                Route::prefix('currencies')->name('currencies.')->group(function () {
                    Route::get('/', [CurrencyController::class, 'index'])
                        ->middleware('permission:settings.read')
                        ->name('index');
                    Route::get('/create', [CurrencyController::class, 'create'])
                        ->middleware('permission:settings.create')
                        ->name('create');
                    Route::post('/', [CurrencyController::class, 'store'])
                        ->middleware('permission:settings.create')
                        ->name('store');
                    Route::get('/{currency}', [CurrencyController::class, 'show'])
                        ->middleware('permission:settings.read')
                        ->name('show');
                    Route::get('/{currency}/edit', [CurrencyController::class, 'edit'])
                        ->middleware('permission:settings.update')
                        ->name('edit');
                    Route::put('/{currency}', [CurrencyController::class, 'update'])
                        ->middleware('permission:settings.update')
                        ->name('update');
                    Route::delete('/{currency}', [CurrencyController::class, 'destroy'])
                        ->middleware('permission:settings.delete')
                        ->name('destroy');
                    Route::patch('/{currency}/set-base', [CurrencyController::class, 'setBase'])
                        ->middleware('permission:settings.update')
                        ->name('set-base');
                });
                
                Route::prefix('tax-rates')->name('tax-rates.')->group(function () {
                    Route::get('/', [TaxRateController::class, 'index'])
                        ->middleware('permission:settings.read')
                        ->name('index');
                    Route::get('/create', [TaxRateController::class, 'create'])
                        ->middleware('permission:settings.create')
                        ->name('create');
                    Route::post('/', [TaxRateController::class, 'store'])
                        ->middleware('permission:settings.create')
                        ->name('store');
                    Route::get('/{taxRate}', [TaxRateController::class, 'show'])
                        ->middleware('permission:settings.read')
                        ->name('show');
                    Route::get('/{taxRate}/edit', [TaxRateController::class, 'edit'])
                        ->middleware('permission:settings.update')
                        ->name('edit');
                    Route::put('/{taxRate}', [TaxRateController::class, 'update'])
                        ->middleware('permission:settings.update')
                        ->name('update');
                    Route::delete('/{taxRate}', [TaxRateController::class, 'destroy'])
                        ->middleware('permission:settings.delete')
                        ->name('destroy');
                    Route::patch('/{taxRate}/toggle-status', [TaxRateController::class, 'toggleStatus'])
                        ->middleware('permission:settings.update')
                        ->name('toggle-status');
                });
            });
        });

    /*
    |--------------------------------------------------------------------------
    | SRPINT 3
    | HRM MODULE (Admin + Finance_HR)
    | Finance_HR: Full CRUD access to HRM
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,finance_hr')
        ->prefix('hrm')
        ->name('hrm.')
        ->group(function () {
            Route::prefix('departments')->name('departments.')->group(function () {
                Route::get('/', [DepartmentController::class, 'index'])
                    ->middleware('permission:hrm.read')
                    ->name('index');
                Route::get('/create', [DepartmentController::class, 'create'])
                    ->middleware('permission:hrm.create')
                    ->name('create');
                Route::post('/', [DepartmentController::class, 'store'])
                    ->middleware('permission:hrm.create')
                    ->name('store');
                Route::get('/{department}', [DepartmentController::class, 'show'])
                    ->middleware('permission:hrm.read')
                    ->name('show');
                Route::get('/{department}/edit', [DepartmentController::class, 'edit'])
                    ->middleware('permission:hrm.update')
                    ->name('edit');
                Route::put('/{department}', [DepartmentController::class, 'update'])
                    ->middleware('permission:hrm.update')
                    ->name('update');
                Route::delete('/{department}', [DepartmentController::class, 'destroy'])
                    ->middleware('permission:hrm.delete')
                    ->name('destroy');
                Route::post('/{department}/assign-manager', [DepartmentController::class, 'assignManager'])
                    ->middleware('permission:hrm.update')
                    ->name('assign-manager');
            });
            
            Route::prefix('positions')->name('positions.')->group(function () {
                Route::get('/', [PositionController::class, 'index'])
                    ->middleware('permission:hrm.read')
                    ->name('index');
                Route::get('/create', [PositionController::class, 'create'])
                    ->middleware('permission:hrm.create')
                    ->name('create');
                Route::post('/', [PositionController::class, 'store'])
                    ->middleware('permission:hrm.create')
                    ->name('store');
                Route::get('/{position}', [PositionController::class, 'show'])
                    ->middleware('permission:hrm.read')
                    ->name('show');
                Route::get('/{position}/edit', [PositionController::class, 'edit'])
                    ->middleware('permission:hrm.update')
                    ->name('edit');
                Route::put('/{position}', [PositionController::class, 'update'])
                    ->middleware('permission:hrm.update')
                    ->name('update');
                Route::delete('/{position}', [PositionController::class, 'destroy'])
                    ->middleware('permission:hrm.delete')
                    ->name('destroy');
            });
            
            Route::prefix('employees')->name('employees.')->group(function () {
                Route::get('/', [EmployeeController::class, 'index'])
                    ->middleware('permission:hrm.read')
                    ->name('index');
                Route::get('/create', [EmployeeController::class, 'create'])
                    ->middleware('permission:hrm.create')
                    ->name('create');
                Route::post('/', [EmployeeController::class, 'store'])
                    ->middleware('permission:hrm.create')
                    ->name('store');
                Route::get('/export', [EmployeeController::class, 'export'])
                    ->middleware('permission:hrm.read')
                    ->name('export');
                Route::get('/{employee}', [EmployeeController::class, 'show'])
                    ->middleware('permission:hrm.read')
                    ->name('show');
                Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])
                    ->middleware('permission:hrm.update')
                    ->name('edit');
                Route::put('/{employee}', [EmployeeController::class, 'update'])
                    ->middleware('permission:hrm.update')
                    ->name('update');
                Route::delete('/{employee}', [EmployeeController::class, 'destroy'])
                    ->middleware('permission:hrm.delete')
                    ->name('destroy');
                Route::post('/{employee}/terminate', [EmployeeController::class, 'terminate'])
                    ->middleware('permission:hrm.update')
                    ->name('terminate');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | SPRINT 3
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

    /*
    |--------------------------------------------------------------------------
    | SPTRINT 4
    | INVENTORY & PRODUCTS (Admin + Operator)
    | Operator: READ, CREATE, UPDATE (No DELETE)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,operator')
        ->prefix('products')
        ->name('products.')
        ->group(function () {
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [ProductCategoryController::class, 'index'])
                    ->middleware('permission:products.read')
                    ->name('index');
                Route::get('/create', [ProductCategoryController::class, 'create'])
                    ->middleware('permission:products.create')
                    ->name('create');
                Route::post('/', [ProductCategoryController::class, 'store'])
                    ->middleware('permission:products.create')
                    ->name('store');
                Route::get('/{category}', [ProductCategoryController::class, 'show'])
                    ->middleware('permission:products.read')
                    ->name('show');
                Route::get('/{category}/edit', [ProductCategoryController::class, 'edit'])
                    ->middleware('permission:products.update')
                    ->name('edit');
                Route::put('/{category}', [ProductCategoryController::class, 'update'])
                    ->middleware('permission:products.update')
                    ->name('update');
                Route::delete('/{category}', [ProductCategoryController::class, 'destroy'])
                    ->middleware('permission:products.delete')
                    ->name('destroy');
            });
            
            Route::get('/', [ProductController::class, 'index'])
                ->middleware('permission:products.read')
                ->name('index');
            Route::get('/create', [ProductController::class, 'create'])
                ->middleware('permission:products.create')
                ->name('create');
            Route::post('/', [ProductController::class, 'store'])
                ->middleware('permission:products.create')
                ->name('store');
            Route::get('/export', [ProductController::class, 'export'])
                ->middleware('permission:products.read')
                ->name('export');
            Route::get('/{product}', [ProductController::class, 'show'])
                ->middleware('permission:products.read')
                ->name('show');
            Route::get('/{product}/edit', [ProductController::class, 'edit'])
                ->middleware('permission:products.update')
                ->name('edit');
            Route::put('/{product}', [ProductController::class, 'update'])
                ->middleware('permission:products.update')
                ->name('update');
            Route::delete('/{product}', [ProductController::class, 'destroy'])
                ->middleware('permission:products.delete')
                ->name('destroy');
            Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
                ->middleware('permission:products.update')
                ->name('toggle-status');
            Route::post('/bulk-update-prices', [ProductController::class, 'bulkUpdatePrices'])
                ->middleware('permission:products.update')
                ->name('bulk-update-prices');
        });

    /*
    |--------------------------------------------------------------------------
    | SPRINT 4
    | BOM & RECIPE (Admin + Operator)
    | Operator: Full CRUD access
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin,operator'])
    ->prefix('inventory')
    ->name('inventory.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | BILL OF MATERIALS (BOM)
        |----------------------------------------------------------------------
        */
        Route::prefix('bom')->name('bom.')->group(function () {
            Route::get('/', [BOMController::class, 'index'])
                ->middleware('permission:inventory.read')
                ->name('index');
            
            Route::get('/create', [BOMController::class, 'create'])
                ->middleware('permission:inventory.create')
                ->name('create');
            
            Route::post('/', [BOMController::class, 'store'])
                ->middleware('permission:inventory.create')
                ->name('store');
            
            Route::get('/export', [BOMController::class, 'export'])
                ->middleware('permission:inventory.read')
                ->name('export');
            
            Route::get('/{bomCode}', [BOMController::class, 'show'])
                ->middleware('permission:inventory.read')
                ->name('show');
            
            Route::get('/{bomCode}/edit', [BOMController::class, 'edit'])
                ->middleware('permission:inventory.update')
                ->name('edit');
            
            Route::put('/{bomCode}', [BOMController::class, 'update'])
                ->middleware('permission:inventory.update')
                ->name('update');
            
            Route::delete('/{bomCode}', [BOMController::class, 'destroy'])
                ->middleware('permission:inventory.delete')
                ->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | RECIPES
        |----------------------------------------------------------------------
        */
        Route::prefix('recipes')->name('recipes.')->group(function () {
            Route::get('/', [RecipeController::class, 'index'])
                ->middleware('permission:inventory.read')
                ->name('index');
            
            Route::get('/create', [RecipeController::class, 'create'])
                ->middleware('permission:inventory.create')
                ->name('create');
            
            Route::post('/', [RecipeController::class, 'store'])
                ->middleware('permission:inventory.create')
                ->name('store');
            
            Route::get('/export', [RecipeController::class, 'export'])
                ->middleware('permission:inventory.read')
                ->name('export');
            
            Route::get('/{recipeCode}', [RecipeController::class, 'show'])
                ->middleware('permission:inventory.read')
                ->name('show');
            
            Route::get('/{recipeCode}/edit', [RecipeController::class, 'edit'])
                ->middleware('permission:inventory.update')
                ->name('edit');
            
            Route::put('/{recipeCode}', [RecipeController::class, 'update'])
                ->middleware('permission:inventory.update')
                ->name('update');
            
            Route::delete('/{recipeCode}', [RecipeController::class, 'destroy'])
                ->middleware('permission:inventory.delete')
                ->name('destroy');
            
            Route::get('/{recipeCode}/print', [RecipeController::class, 'print'])
                ->middleware('permission:inventory.read')
                ->name('print');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | SPRINT 5
    | PRODUCTION MANAGEMENT ROUTES 
    | Access: Admin + Operator
    | Operator: Full CRUD access except delete
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin,operator'])
        ->prefix('production')
        ->name('production.')
        ->group(function () {
            
            /*
            |----------------------------------------------------------------------
            | PRODUCTION PLANNING (MRP)
            |----------------------------------------------------------------------
            */
            Route::prefix('planning')->name('planning.')->group(function () {
                Route::get('/', [ProductionPlanningController::class, 'index'])
                    ->middleware('permission:production.read')
                    ->name('index');
                
                Route::get('/create', [ProductionPlanningController::class, 'create'])
                    ->middleware('permission:production.create')
                    ->name('create');
                
                Route::post('/', [ProductionPlanningController::class, 'store'])
                    ->middleware('permission:production.create')
                    ->name('store');
                
                Route::get('/export', [ProductionPlanningController::class, 'export'])
                    ->middleware('permission:production.read')
                    ->name('export');
                
                Route::get('/{planCode}', [ProductionPlanningController::class, 'show'])
                    ->middleware('permission:production.read')
                    ->name('show');
                
                Route::get('/{planCode}/edit', [ProductionPlanningController::class, 'edit'])
                    ->middleware('permission:production.update')
                    ->name('edit');
                
                Route::put('/{planCode}', [ProductionPlanningController::class, 'update'])
                    ->middleware('permission:production.update')
                    ->name('update');
                
                Route::delete('/{planCode}', [ProductionPlanningController::class, 'destroy'])
                    ->middleware('permission:production.delete')
                    ->name('destroy');
                
                Route::post('/{planCode}/approve', [ProductionPlanningController::class, 'approve'])
                    ->middleware('permission:production.update')
                    ->name('approve');
            });
            
            /*
            |----------------------------------------------------------------------
            | WORK ORDERS
            |----------------------------------------------------------------------
            */
            Route::prefix('work-orders')->name('work-orders.')->group(function () {
                Route::get('/', [WorkOrderController::class, 'index'])
                    ->middleware('permission:production.read')
                    ->name('index');
                
                Route::get('/create', [WorkOrderController::class, 'create'])
                    ->middleware('permission:production.create')
                    ->name('create');
                
                Route::post('/', [WorkOrderController::class, 'store'])
                    ->middleware('permission:production.create')
                    ->name('store');
                
                Route::get('/export', [WorkOrderController::class, 'export'])
                    ->middleware('permission:production.read')
                    ->name('export');
                
                Route::get('/{woCode}', [WorkOrderController::class, 'show'])
                    ->middleware('permission:production.read')
                    ->name('show');
                
                Route::get('/{woCode}/edit', [WorkOrderController::class, 'edit'])
                    ->middleware('permission:production.update')
                    ->name('edit');
                
                Route::put('/{woCode}', [WorkOrderController::class, 'update'])
                    ->middleware('permission:production.update')
                    ->name('update');
                
                Route::delete('/{woCode}', [WorkOrderController::class, 'destroy'])
                    ->middleware('permission:production.delete')
                    ->name('destroy');
                
                Route::post('/{woCode}/start', [WorkOrderController::class, 'start'])
                    ->middleware('permission:production.update')
                    ->name('start');
                
                Route::post('/{woCode}/complete', [WorkOrderController::class, 'complete'])
                    ->middleware('permission:production.update')
                    ->name('complete');
                
                Route::post('/{woCode}/cancel', [WorkOrderController::class, 'cancel'])
                    ->middleware('permission:production.update')
                    ->name('cancel');
                
                Route::get('/{woCode}/print', [WorkOrderController::class, 'print'])
                    ->middleware('permission:production.read')
                    ->name('print');
            });
            
            /*
            |----------------------------------------------------------------------
            | BATCH TRACKING
            |----------------------------------------------------------------------
            */
            Route::prefix('batches')->name('batches.')->group(function () {
                Route::get('/', [BatchController::class, 'index'])
                    ->middleware('permission:production.read')
                    ->name('index');
                
                Route::get('/create', [BatchController::class, 'create'])
                    ->middleware('permission:production.create')
                    ->name('create');
                
                Route::post('/', [BatchController::class, 'store'])
                    ->middleware('permission:production.create')
                    ->name('store');
                
                Route::get('/export', [BatchController::class, 'export'])
                    ->middleware('permission:production.read')
                    ->name('export');
                
                Route::get('/{batchCode}', [BatchController::class, 'show'])
                    ->middleware('permission:production.read')
                    ->name('show');
                
                Route::get('/{batchCode}/edit', [BatchController::class, 'edit'])
                    ->middleware('permission:production.update')
                    ->name('edit');
                
                Route::put('/{batchCode}', [BatchController::class, 'update'])
                    ->middleware('permission:production.update')
                    ->name('update');
                
                Route::delete('/{batchCode}', [BatchController::class, 'destroy'])
                    ->middleware('permission:production.delete')
                    ->name('destroy');
                
                Route::get('/{batchCode}/print-label', [BatchController::class, 'printLabel'])
                    ->middleware('permission:production.read')
                    ->name('print-label');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | SPRINT 5
    | LOT TRACKING ROUTES
    | Access: Admin + Operator
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin,operator'])
        ->prefix('inventory/lots')
        ->name('inventory.lots.')
        ->group(function () {
            Route::get('/', [LotController::class, 'index'])
                ->middleware('permission:inventory.read')
                ->name('index');
            
            Route::get('/create', [LotController::class, 'create'])
                ->middleware('permission:inventory.create')
                ->name('create');
            
            Route::post('/', [LotController::class, 'store'])
                ->middleware('permission:inventory.create')
                ->name('store');
            
            Route::get('/export', [LotController::class, 'export'])
                ->middleware('permission:inventory.read')
                ->name('export');
            
            Route::get('/{lotCode}', [LotController::class, 'show'])
                ->middleware('permission:inventory.read')
                ->name('show');
            
            Route::get('/{lotCode}/edit', [LotController::class, 'edit'])
                ->middleware('permission:inventory.update')
                ->name('edit');
            
            Route::put('/{lotCode}', [LotController::class, 'update'])
                ->middleware('permission:inventory.update')
                ->name('update');
            
            Route::delete('/{lotCode}', [LotController::class, 'destroy'])
                ->middleware('permission:inventory.delete')
                ->name('destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | SPRINT 6
    | WAREHOUSE & INVENTORY
    | Access: Admin + Operator
    | Operator: Full CRUD access except warehouse deletion
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:admin,operator'])
        ->prefix('inventory')
        ->name('inventory.')
        ->group(function () {
            
            /*
            |----------------------------------------------------------------------
            | WAREHOUSES
            |----------------------------------------------------------------------
            */
            Route::prefix('warehouses')->name('warehouses.')->group(function () {
                Route::get('/', [WarehouseController::class, 'index'])
                    ->middleware('permission:inventory.read')
                    ->name('index');
                
                Route::get('/create', [WarehouseController::class, 'create'])
                    ->middleware('permission:inventory.create')
                    ->name('create');
                
                Route::post('/', [WarehouseController::class, 'store'])
                    ->middleware('permission:inventory.create')
                    ->name('store');
                
                Route::get('/export', [WarehouseController::class, 'export'])
                    ->middleware('permission:inventory.read')
                    ->name('export');
                
                Route::get('/{warehouseCode}', [WarehouseController::class, 'show'])
                    ->middleware('permission:inventory.read')
                    ->name('show');
                
                Route::get('/{warehouseCode}/edit', [WarehouseController::class, 'edit'])
                    ->middleware('permission:inventory.update')
                    ->name('edit');
                
                Route::put('/{warehouseCode}', [WarehouseController::class, 'update'])
                    ->middleware('permission:inventory.update')
                    ->name('update');
                
                Route::delete('/{warehouseCode}', [WarehouseController::class, 'destroy'])
                    ->middleware('permission:inventory.delete')
                    ->name('destroy');
                
                Route::patch('/{warehouseCode}/toggle-status', [WarehouseController::class, 'toggleStatus'])
                    ->middleware('permission:inventory.update')
                    ->name('toggle-status');
            });
            
            /*
            |----------------------------------------------------------------------
            | WAREHOUSE LOCATIONS
            |----------------------------------------------------------------------
            */
            Route::prefix('locations')->name('locations.')->group(function () {
                Route::get('/', [WarehouseLocationController::class, 'index'])
                    ->middleware('permission:inventory.read')
                    ->name('index');
                
                Route::get('/create', [WarehouseLocationController::class, 'create'])
                    ->middleware('permission:inventory.create')
                    ->name('create');
                
                Route::post('/', [WarehouseLocationController::class, 'store'])
                    ->middleware('permission:inventory.create')
                    ->name('store');
                
                Route::get('/export', [WarehouseLocationController::class, 'export'])
                    ->middleware('permission:inventory.read')
                    ->name('export');
                
                Route::get('/{locationCode}', [WarehouseLocationController::class, 'show'])
                    ->middleware('permission:inventory.read')
                    ->name('show');
                
                Route::get('/{locationCode}/edit', [WarehouseLocationController::class, 'edit'])
                    ->middleware('permission:inventory.update')
                    ->name('edit');
                
                Route::put('/{locationCode}', [WarehouseLocationController::class, 'update'])
                    ->middleware('permission:inventory.update')
                    ->name('update');
                
                Route::delete('/{locationCode}', [WarehouseLocationController::class, 'destroy'])
                    ->middleware('permission:inventory.delete')
                    ->name('destroy');
            });
            
            /*
            |----------------------------------------------------------------------
            | INVENTORY (STOCK TRACKING)
            |----------------------------------------------------------------------
            */
            Route::prefix('stock')->name('stock.')->group(function () {
                Route::get('/', [InventoryController::class, 'index'])
                    ->middleware('permission:inventory.read')
                    ->name('index');
                
                Route::get('/export', [InventoryController::class, 'export'])
                    ->middleware('permission:inventory.read')
                    ->name('export');
                
                Route::get('/low-stock', [InventoryController::class, 'lowStockReport'])
                    ->middleware('permission:inventory.read')
                    ->name('low-stock');
                
                Route::get('/valuation', [InventoryController::class, 'valuationReport'])
                    ->middleware('permission:inventory.read')
                    ->name('valuation');
                
                Route::get('/{inventoryId}', [InventoryController::class, 'show'])
                    ->middleware('permission:inventory.read')
                    ->name('show');
                
                Route::get('/{inventoryId}/edit', [InventoryController::class, 'edit'])
                    ->middleware('permission:inventory.update')
                    ->name('edit');
                
                Route::put('/{inventoryId}', [InventoryController::class, 'update'])
                    ->middleware('permission:inventory.update')
                    ->name('update');
            });
            
            /*
            |----------------------------------------------------------------------
            | STOCK MOVEMENTS
            |----------------------------------------------------------------------
            */
            Route::prefix('movements')->name('movements.')->group(function () {
                Route::get('/', [StockMovementController::class, 'index'])
                    ->middleware('permission:inventory.read')
                    ->name('index');
                
                Route::get('/create', [StockMovementController::class, 'create'])
                    ->middleware('permission:inventory.create')
                    ->name('create');
                
                Route::post('/', [StockMovementController::class, 'store'])
                    ->middleware('permission:inventory.create')
                    ->name('store');
                
                Route::get('/export', [StockMovementController::class, 'export'])
                    ->middleware('permission:inventory.read')
                    ->name('export');
                
                Route::get('/{movementCode}', [StockMovementController::class, 'show'])
                    ->middleware('permission:inventory.read')
                    ->name('show');
                
                Route::delete('/{movementCode}', [StockMovementController::class, 'destroy'])
                    ->middleware('permission:inventory.delete')
                    ->name('destroy');
            });
        });

    /*
    |--------------------------------------------------------------------------
    | OPERATOR DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:operator')
        ->prefix('operator')
        ->name('operator.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'operator'])->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | FINANCE/HR DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:finance_hr')
        ->prefix('finance-hr')
        ->name('finance_hr.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'financeHr'])->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | TEMPLATE ROUTES (Development Only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('template')->name('template.')->group(function () {
        Route::get('/alerts', [TemplateController::class, 'alerts'])->name('alerts');
        Route::get('/badge', [TemplateController::class, 'badge'])->name('badge');
        Route::get('/breadcrumb', [TemplateController::class, 'breadcrumb'])->name('breadcrumb');
        Route::get('/buttonGroup', [TemplateController::class, 'buttonGroup'])->name('buttonGroup');
        Route::get('/buttons', [TemplateController::class, 'buttons'])->name('buttons');
        Route::get('/cards', [TemplateController::class, 'cards'])->name('cards');
        Route::get('/dropdowns', [TemplateController::class, 'dropdowns'])->name('dropdowns');
        Route::get('/imageFigures', [TemplateController::class, 'imageFigures'])->name('imageFigures');
        Route::get('/linkInteraction', [TemplateController::class, 'linkInteraction'])->name('linkInteraction');
        Route::get('/listGroup', [TemplateController::class, 'listGroup'])->name('listGroup');
        Route::get('/navs', [TemplateController::class, 'navs'])->name('navs');
        Route::get('/objectFit', [TemplateController::class, 'objectFit'])->name('objectFit');
        Route::get('/pagination', [TemplateController::class, 'pagination'])->name('pagination');
        Route::get('/popovers', [TemplateController::class, 'popovers'])->name('popovers');
        Route::get('/progress', [TemplateController::class, 'progress'])->name('progress');
        Route::get('/spinners', [TemplateController::class, 'spinners'])->name('spinners');
        Route::get('/toasts', [TemplateController::class, 'toasts'])->name('toasts');
        Route::get('/tooltips', [TemplateController::class, 'tooltips'])->name('tooltips');
        Route::get('/typography', [TemplateController::class, 'typography'])->name('typography');
        Route::get('/modals', [TemplateController::class, 'modals'])->name('modals');
        Route::get('/offcanvas', [TemplateController::class, 'offcanvas'])->name('offcanvas');
        Route::get('/placeholder', [TemplateController::class, 'placeholder'])->name('placeholder');
        Route::get('/scrollspy', [TemplateController::class, 'scrollspy'])->name('scrollspy');
        Route::get('/sweetAlert', [TemplateController::class, 'sweetAlert'])->name('sweetAlert');
        Route::get('/inputs', [TemplateController::class, 'inputs'])->name('inputs');
        Route::get('/checkRadios', [TemplateController::class, 'checkRadios'])->name('checkRadios');
        Route::get('/inputGroup', [TemplateController::class, 'inputGroup'])->name('inputGroup');
        Route::get('/select', [TemplateController::class, 'select'])->name('select');
        Route::get('/rangeSlider', [TemplateController::class, 'rangeSlider'])->name('rangeSlider');
        Route::get('/inputMask', [TemplateController::class, 'inputMask'])->name('inputMask');
        Route::get('/fileUploads', [TemplateController::class, 'fileUploads'])->name('fileUploads');
        Route::get('/datetimePicker', [TemplateController::class, 'datetimePicker'])->name('datetimePicker');
        Route::get('/colorPicker', [TemplateController::class, 'colorPicker'])->name('colorPicker');
        Route::get('/formAdvanced', [TemplateController::class, 'formAdvanced'])->name('formAdvanced');
        Route::get('/formLayout', [TemplateController::class, 'formLayout'])->name('formLayout');
        Route::get('/createPassword', [TemplateController::class, 'createPassword'])->name('createPassword');
        Route::get('/lockScreen', [TemplateController::class, 'lockScreen'])->name('lockScreen');
        Route::get('/resetPassword', [TemplateController::class, 'resetPassword'])->name('resetPassword');
        Route::get('/signIn', [TemplateController::class, 'signIn'])->name('signIn');
        Route::get('/signUp', [TemplateController::class, 'signUp'])->name('signUp');
    });
});