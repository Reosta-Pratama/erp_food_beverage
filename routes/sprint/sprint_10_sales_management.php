<?php

use App\Http\Controllers\Business\CustomerController;
use App\Http\Controllers\Business\SalesOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 10: CUSTOMER & SALES MANAGEMENT MODULE
| - Customers (CRUD, Export, Toggle Status)
| - Sales Orders (CRUD, Export, Print, Approve, Cancel, Process)
| - Order Processing
|
| Access: 
| - Customers: Admin + Finance_HR
| - Sales Orders: Admin + Finance_HR + Operator (partial)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| CUSTOMERS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr', 'permission:customers.manage'])
    ->prefix('sales/customers')
    ->name('sales.customers.')
    ->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/export', [CustomerController::class, 'export'])->name('export');
        Route::get('/{customerCode}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customerCode}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customerCode}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customerCode}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::patch('/{customerCode}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');
    });

/*
|--------------------------------------------------------------------------
| SALES ORDERS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr,operator', 'permission:sales_orders.manage'])
    ->prefix('sales/orders')
    ->name('sales.orders.')
    ->group(function () {
        Route::get('/', [SalesOrderController::class, 'index'])->name('index');
        Route::get('/create', [SalesOrderController::class, 'create'])->name('create');
        Route::post('/', [SalesOrderController::class, 'store'])->name('store');
        Route::get('/export', [SalesOrderController::class, 'export'])->name('export');
        Route::get('/{soCode}', [SalesOrderController::class, 'show'])->name('show');
        Route::get('/{soCode}/edit', [SalesOrderController::class, 'edit'])->name('edit');
        Route::put('/{soCode}', [SalesOrderController::class, 'update'])->name('update');
        Route::delete('/{soCode}', [SalesOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{soCode}/print', [SalesOrderController::class, 'print'])->name('print');
        
        // Status actions
        Route::post('/{soCode}/approve', [SalesOrderController::class, 'approve'])->name('approve');
        Route::post('/{soCode}/process', [SalesOrderController::class, 'process'])->name('process');
        Route::post('/{soCode}/cancel', [SalesOrderController::class, 'cancel'])->name('cancel');
    });