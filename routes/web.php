<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MAIN ROUTES FILE
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login.show');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Auto redirect to role-based dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | LOAD SPRINT ROUTES
    |--------------------------------------------------------------------------
    */
    
    // SPRINT 1: User Management, Roles, Permissions, Logs
    require __DIR__.'/sprint/sprint_01_user_management.php';
    
    // SPRINT 2: Settings (Company Profile, UOM, Currency, Tax)
    require __DIR__.'/sprint/sprint_02_settings.php';
    
    // SPRINT 3: HRM (Departments, Positions, Employees, ESS)
    require __DIR__.'/sprint/sprint_03_hrm.php';
    
    // SPRINT 4: Products & BOM/Recipe
    require __DIR__.'/sprint/sprint_04_products.php';
    
    // SPRINT 5: Production Management
    require __DIR__.'/sprint/sprint_05_production.php';
    
    // SPRINT 6: Warehouse & Inventory
    require __DIR__.'/sprint/sprint_06_inventory.php';

    // SPRINT 7: Quality Control
    require __DIR__.'/sprint/sprint_07_quality_control.php';
    
    // SPRINT 8: Expiry Management
    require __DIR__.'/sprint/sprint_08_expiry_management.php';

    // SPRINT 9: Supplier & Purchase Management
    require __DIR__.'/sprint/sprint_09_purchase_management.php';

    // SPRINT 10: Customer & Sales Management
    require __DIR__.'/sprint/sprint_10_sales_management.php';

    // SPRINT 11: Delivery & Distribution
    require __DIR__.'/sprint/sprint_11_delivery_distribution.php';

    // ROLE-BASED DASHBOARDS
    require __DIR__.'/sprint/dashboards.php';
    
    // DEVELOPMENT TEMPLATES (Optional - can be disabled in production)
    if (config('app.debug')) {
        require __DIR__.'/sprint/templates.php';
    }
});