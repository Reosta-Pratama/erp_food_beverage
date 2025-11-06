<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Redirect to appropriate dashboard based on role
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
            
            // User Management
            Route::resource('users', UserController::class);
            Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])
                ->name('users.reset-password');
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                ->name('users.toggle-status');
            
            // Role Management
            Route::resource('roles', RoleController::class);
            
            // Permission Management
            Route::resource('permissions', PermissionController::class);
            
            // Logs Management
            Route::prefix('logs')->name('logs.')->group(function () {
                // Activity Logs
                Route::get('activity', [ActivityLogController::class, 'index'])
                    ->name('activity');
                Route::post('activity/clear', [ActivityLogController::class, 'clear'])
                    ->name('activity.clear');
                
                // Audit Logs
                Route::get('audit', [AuditLogController::class, 'index'])
                    ->name('audit');
                Route::get('audit/{auditLog}', [AuditLogController::class, 'show'])
                    ->name('audit.show');
                Route::post('audit/clear', [AuditLogController::class, 'clear'])
                    ->name('audit.clear');
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