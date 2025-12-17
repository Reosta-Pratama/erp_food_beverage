<?php

use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\PaymentReconciliationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 15: PAYMENT PROCESSING MODULE
| - Payment Management (CRUD, Void, Export, Print, Summary)
| - Payment Methods Analysis
| - Payment Reconciliation (Bank Reconciliation, Cash Flow, Method Analysis)
|
| Access: Admin + Finance_HR (Operator = No Access)
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin,finance_hr', 'permission:payments.manage'])
    ->prefix('finance/payments')
    ->name('finance.payments.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | PAYMENT MANAGEMENT
        |----------------------------------------------------------------------
        */
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/payable', [PaymentController::class, 'storePayable'])->name('store-payable');
        Route::post('/receivable', [PaymentController::class, 'storeReceivable'])->name('store-receivable');
        Route::get('/export', [PaymentController::class, 'export'])->name('export');
        Route::get('/summary', [PaymentController::class, 'summary'])->name('summary');
        Route::get('/{paymentCode}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{paymentCode}/print', [PaymentController::class, 'print'])->name('print');
        
        // Payment actions
        Route::post('/{paymentCode}/void', [PaymentController::class, 'void'])->name('void');
    });

/*
|--------------------------------------------------------------------------
| PAYMENT RECONCILIATION
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,finance_hr', 'permission:payments.manage'])
    ->prefix('finance/reconciliation')
    ->name('finance.reconciliation.')
    ->group(function () {
        
        Route::get('/', [PaymentReconciliationController::class, 'index'])->name('index');
        Route::get('/bank', [PaymentReconciliationController::class, 'bankReconciliation'])->name('bank');
        Route::get('/cash-flow', [PaymentReconciliationController::class, 'cashFlowReport'])->name('cash-flow');
        Route::get('/method-analysis', [PaymentReconciliationController::class, 'methodAnalysis'])->name('method-analysis');
        Route::get('/unreconciled', [PaymentReconciliationController::class, 'unreconciled'])->name('unreconciled');
        Route::get('/export', [PaymentReconciliationController::class, 'exportReconciliation'])->name('export');
        
        // Reconciliation actions
        Route::post('/mark-reconciled', [PaymentReconciliationController::class, 'markReconciled'])->name('mark-reconciled');
    });