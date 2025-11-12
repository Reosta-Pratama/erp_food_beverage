<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\Settings\CurrencyController;
use App\Http\Controllers\Admin\Settings\TaxRateController;
use App\Http\Controllers\Admin\Settings\UnitOfMeasureController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Public)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Show login form
    Route::get('/', [AuthController::class, 'showLogin'])->name('login.show');
    Route::get('/login', [AuthController::class, 'showLogin']);

    // Handle login submission
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
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/{user}', [UserController::class, 'show'])->name('show');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
                
                // Additional actions
                Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])
                    ->name('reset-password');
                Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                    ->name('toggle-status');
            });
            
            /*
            |----------------------------------------------------------------------
            | ROLE MANAGEMENT
            |----------------------------------------------------------------------
            */
            Route::prefix('roles')->name('roles.')->group(function () {
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
            | PERMISSION MANAGEMENT
            |----------------------------------------------------------------------
            */
            Route::prefix('permissions')->name('permissions.')->group(function () {
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
                Route::prefix('activity')->name('activity.')->group(function () {
                    Route::get('/', [ActivityLogController::class, 'index'])->name('index');
                    Route::post('/clear', [ActivityLogController::class, 'clear'])->name('clear');
                    Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
                });
                
                // Audit Logs
                Route::prefix('audit')->name('audit.')->group(function () {
                    Route::get('/', [AuditLogController::class, 'index'])->name('index');
                    Route::get('/trail', [AuditLogController::class, 'trail'])->name('trail');
                    Route::get('/statistics', [AuditLogController::class, 'statistics'])->name('statistics');
                    Route::get('/export', [AuditLogController::class, 'export'])->name('export');
                    Route::get('/{auditLog}', [AuditLogController::class, 'show'])->name('show');
                    Route::post('/clear', [AuditLogController::class, 'clear'])->name('clear');
                });
            });

            /*
            |--------------------------------------------------------------------------
            | SETTINGS MODULE
            |--------------------------------------------------------------------------
            */
            Route::prefix('settings')->name('settings.')->group(function () {
                
                // Company Profile (Single Record)
                // Route::prefix('company-profile')->name('company-profile.')->group(function () {
                //     Route::get('/', [CompanyProfileController::class, 'index'])->name('index');
                //     Route::get('/edit', [CompanyProfileController::class, 'edit'])->name('edit');
                //     Route::put('/', [CompanyProfileController::class, 'update'])->name('update');
                //     Route::delete('/logo', [CompanyProfileController::class, 'deleteLogo'])->name('delete-logo');
                // });
                
                // Units of Measure
                Route::prefix('uom')->name('uom.')->group(function () {
                    Route::get('/', [UnitOfMeasureController::class, 'index'])->name('index');
                    Route::get('/create', [UnitOfMeasureController::class, 'create'])->name('create');
                    Route::post('/', [UnitOfMeasureController::class, 'store'])->name('store');
                    Route::get('/{uom}', [UnitOfMeasureController::class, 'show'])->name('show');
                    Route::get('/{uom}/edit', [UnitOfMeasureController::class, 'edit'])->name('edit');
                    Route::put('/{uom}', [UnitOfMeasureController::class, 'update'])->name('update');
                    Route::delete('/{uom}', [UnitOfMeasureController::class, 'destroy'])->name('destroy');
                });
                
                // Currencies
                Route::prefix('currencies')->name('currencies.')->group(function () {
                    Route::get('/', [CurrencyController::class, 'index'])->name('index');
                    Route::get('/create', [CurrencyController::class, 'create'])->name('create');
                    Route::post('/', [CurrencyController::class, 'store'])->name('store');
                    Route::get('/{currency}', [CurrencyController::class, 'show'])->name('show');
                    Route::get('/{currency}/edit', [CurrencyController::class, 'edit'])->name('edit');
                    Route::put('/{currency}', [CurrencyController::class, 'update'])->name('update');
                    Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('destroy');
                    
                    // Additional actions
                    Route::patch('/{currency}/set-base', [CurrencyController::class, 'setBase'])->name('set-base');
                });
                
                // Tax Rates
                Route::prefix('tax-rates')->name('tax-rates.')->group(function () {
                    Route::get('/', [TaxRateController::class, 'index'])->name('index');
                    Route::get('/create', [TaxRateController::class, 'create'])->name('create');
                    Route::post('/', [TaxRateController::class, 'store'])->name('store');
                    Route::get('/{taxRate}', [TaxRateController::class, 'show'])->name('show');
                    Route::get('/{taxRate}/edit', [TaxRateController::class, 'edit'])->name('edit');
                    Route::put('/{taxRate}', [TaxRateController::class, 'update'])->name('update');
                    Route::delete('/{taxRate}', [TaxRateController::class, 'destroy'])->name('destroy');
                    
                    // Additional actions
                    Route::patch('/{taxRate}/toggle-status', [TaxRateController::class, 'toggleStatus'])->name('toggle-status');
                });
            });
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
            
            // Future operator routes here
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
            
            // Future finance/HR routes here
            // Route::resource('employees', EmployeeController::class);
            // Route::resource('payroll', PayrollController::class);
        });





























    // Design Web
    Route::prefix('template')
        ->name('template.')
        ->group(function () {
            // Basic
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

            // Advanced
            Route::get('/modals', [TemplateController::class, 'modals'])->name('modals');
            Route::get('/offcanvas', [TemplateController::class, 'offcanvas'])->name('offcanvas');
            Route::get('/placeholder', [TemplateController::class, 'placeholder'])->name('placeholder');
            Route::get('/scrollspy', [TemplateController::class, 'scrollspy'])->name('scrollspy');
            Route::get('/sweetAlert', [TemplateController::class, 'sweetAlert'])->name('sweetAlert');

            // Forms
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

            // Pages

            // Authtentication
            Route::get('/createPassword', [TemplateController::class, 'createPassword'])->name('createPassword');
            Route::get('/lockScreen', [TemplateController::class, 'lockScreen'])->name('lockScreen');
            Route::get('/resetPassword', [TemplateController::class, 'resetPassword'])->name('resetPassword');
            Route::get('/signIn', [TemplateController::class, 'signIn'])->name('signIn');
            Route::get('/signUp', [TemplateController::class, 'signUp'])->name('signUp');

            // Errors
    });
});