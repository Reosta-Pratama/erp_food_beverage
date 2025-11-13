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
    | ADMIN ROUTES
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
            | USER MANAGEMENT
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
                
                // Additional actions
                Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
                    ->middleware('permission:users.update')
                    ->name('reset-password');
                    
                Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                    ->middleware('permission:users.update')
                    ->name('toggle-status');
            });
            
            /*
            |----------------------------------------------------------------------
            | ROLE MANAGEMENT
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
            | PERMISSION MANAGEMENT
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
                        
                    Route::get('/trail', [AuditLogController::class, 'trail'])
                        ->middleware('permission:logs.read')
                        ->name('trail');
                        
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
            | SETTINGS MODULE
            |----------------------------------------------------------------------
            */
            Route::prefix('settings')->name('settings.')->group(function () {
                
                // Company Profile
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
                
                // Units of Measure
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
                
                // Currencies
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
                
                // Tax Rates
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
    | PRODUCT MANAGEMENT MODULE (Multi-Role Access)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,operator')
        ->prefix('products')
        ->name('products.')
        ->group(function () {
            
            // Product Categories
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
            
            // Products
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
    |  HRM MODULE (Multi-Role Access)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,finance_hr')
        ->prefix('hrm')
        ->name('hrm.')
        ->group(function () {
            // Departments
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
            
            // Positions
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
            
            // Employees
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
    | OPERATOR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:operator')
        ->prefix('operator')
        ->name('operator.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'operator'])->name('dashboard');
            
            // Future operator-specific routes
            // Route::resource('work-orders', WorkOrderController::class);
            // Route::resource('inventory', InventoryController::class);
        });

    /*
    |--------------------------------------------------------------------------
    | FINANCE/HR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:finance_hr')
        ->prefix('finance-hr')
        ->name('finance_hr.')
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'financeHr'])->name('dashboard');
            
            // Future finance/HR routes
            // Route::resource('payroll', PayrollController::class);
            // Route::resource('budgets', BudgetController::class);
        });

    /*
    |--------------------------------------------------------------------------
    | TEMPLATE ROUTES (Development Only - Remove in Production)
    |--------------------------------------------------------------------------
    */
    Route::prefix('template')->name('template.')->group(function () {
        // Basic Components
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

        // Advanced Components
        Route::get('/modals', [TemplateController::class, 'modals'])->name('modals');
        Route::get('/offcanvas', [TemplateController::class, 'offcanvas'])->name('offcanvas');
        Route::get('/placeholder', [TemplateController::class, 'placeholder'])->name('placeholder');
        Route::get('/scrollspy', [TemplateController::class, 'scrollspy'])->name('scrollspy');
        Route::get('/sweetAlert', [TemplateController::class, 'sweetAlert'])->name('sweetAlert');

        // Form Components
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

        // Authentication Pages
        Route::get('/createPassword', [TemplateController::class, 'createPassword'])->name('createPassword');
        Route::get('/lockScreen', [TemplateController::class, 'lockScreen'])->name('lockScreen');
        Route::get('/resetPassword', [TemplateController::class, 'resetPassword'])->name('resetPassword');
        Route::get('/signIn', [TemplateController::class, 'signIn'])->name('signIn');
        Route::get('/signUp', [TemplateController::class, 'signUp'])->name('signUp');
    });
});