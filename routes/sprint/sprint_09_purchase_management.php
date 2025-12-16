<?php

use App\Http\Controllers\Business\PurchaseOrderController;
use App\Http\Controllers\Business\PurchaseReceiptController;
use App\Http\Controllers\Business\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 9: SUPPLIER & PURCHASE MANAGEMENT MODULE
| - Suppliers & Vendors (CRUD, Export, Toggle Status)
| - Purchase Orders (CRUD, Export, Print, Approve, Cancel)
| - Purchase Receipts (CRUD, Export, Print)
|
| Access: 
| - Suppliers: Admin + Finance_HR
| - Purchase Orders: Admin + Finance_HR
| - Purchase Receipts: Admin + Operator
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| SUPPLIERS & VENDORS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr', 'permission:suppliers.manage'])
    ->prefix('purchase/suppliers')
    ->name('purchase.suppliers.')
    ->group(function (): void {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/export', [SupplierController::class, 'export'])->name('export');
        Route::get('/{supplierCode}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{supplierCode}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{supplierCode}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplierCode}', [SupplierController::class, 'destroy'])->name('destroy');
        Route::patch('/{supplierCode}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('toggle-status');
    });

/*
|--------------------------------------------------------------------------
| PURCHASE ORDERS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr', 'permission:purchase_orders.manage'])
    ->prefix('purchase/orders')
    ->name('purchase.orders.')
    ->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/export', [PurchaseOrderController::class, 'export'])->name('export');
        Route::get('/{poCode}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{poCode}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{poCode}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{poCode}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{poCode}/print', [PurchaseOrderController::class, 'print'])->name('print');
        
        // Status actions
        Route::post('/{poCode}/approve', [PurchaseOrderController::class, 'approve'])->name('approve');
        Route::post('/{poCode}/cancel', [PurchaseOrderController::class, 'cancel'])->name('cancel');
    });

/*
|--------------------------------------------------------------------------
| PURCHASE RECEIPTS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:purchase_orders.manage'])
    ->prefix('purchase/receipts')
    ->name('purchase.receipts.')
    ->group(function () {
        Route::get('/', [PurchaseReceiptController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseReceiptController::class, 'create'])->name('create');
        Route::post('/', [PurchaseReceiptController::class, 'store'])->name('store');
        Route::get('/export', [PurchaseReceiptController::class, 'export'])->name('export');
        Route::get('/{receiptCode}', [PurchaseReceiptController::class, 'show'])->name('show');
        Route::get('/{receiptCode}/edit', [PurchaseReceiptController::class, 'edit'])->name('edit');
        Route::put('/{receiptCode}', [PurchaseReceiptController::class, 'update'])->name('update');
        Route::delete('/{receiptCode}', [PurchaseReceiptController::class, 'destroy'])->name('destroy');
        Route::get('/{receiptCode}/print', [PurchaseReceiptController::class, 'print'])->name('print');
    });