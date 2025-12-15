<?php

use App\Http\Controllers\Business\PurchaseReturnController;
use App\Http\Controllers\Business\SalesReturnController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 12: RETURNS MANAGEMENT MODULE
| - Purchase Returns (CRUD, Export, Print, Approve, Reject)
| - Sales Returns (CRUD, Export, Print, Approve, Reject, Process Refund)
|
| Access: 
| - Purchase Returns: Admin + Finance_HR
| - Sales Returns: Admin + Finance_HR + Operator (partial)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PURCHASE RETURNS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr', 'permission:purchase_orders.manage'])
    ->prefix('purchase/returns')
    ->name('purchase.returns.')
    ->group(function () {
        Route::get('/', [PurchaseReturnController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseReturnController::class, 'create'])->name('create');
        Route::post('/', [PurchaseReturnController::class, 'store'])->name('store');
        Route::get('/export', [PurchaseReturnController::class, 'export'])->name('export');
        Route::get('/{prCode}', [PurchaseReturnController::class, 'show'])->name('show');
        Route::get('/{prCode}/edit', [PurchaseReturnController::class, 'edit'])->name('edit');
        Route::put('/{prCode}', [PurchaseReturnController::class, 'update'])->name('update');
        Route::delete('/{prCode}', [PurchaseReturnController::class, 'destroy'])->name('destroy');
        Route::get('/{prCode}/print', [PurchaseReturnController::class, 'print'])->name('print');
        
        // Status actions
        Route::post('/{prCode}/approve', [PurchaseReturnController::class, 'approve'])->name('approve');
        Route::post('/{prCode}/reject', [PurchaseReturnController::class, 'reject'])->name('reject');
    });

/*
|--------------------------------------------------------------------------
| SALES RETURNS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr,operator', 'permission:sales_orders.manage'])
    ->prefix('sales/returns')
    ->name('sales.returns.')
    ->group(function () {
        Route::get('/', [SalesReturnController::class, 'index'])->name('index');
        Route::get('/create', [SalesReturnController::class, 'create'])->name('create');
        Route::post('/', [SalesReturnController::class, 'store'])->name('store');
        Route::get('/export', [SalesReturnController::class, 'export'])->name('export');
        Route::get('/{srCode}', [SalesReturnController::class, 'show'])->name('show');
        Route::get('/{srCode}/edit', [SalesReturnController::class, 'edit'])->name('edit');
        Route::put('/{srCode}', [SalesReturnController::class, 'update'])->name('update');
        Route::delete('/{srCode}', [SalesReturnController::class, 'destroy'])->name('destroy');
        Route::get('/{srCode}/print', [SalesReturnController::class, 'print'])->name('print');
        
        // Status actions
        Route::post('/{srCode}/approve', [SalesReturnController::class, 'approve'])->name('approve');
        Route::post('/{srCode}/reject', [SalesReturnController::class, 'reject'])->name('reject');
        Route::post('/{srCode}/process-refund', [SalesReturnController::class, 'processRefund'])->name('process-refund');
    });