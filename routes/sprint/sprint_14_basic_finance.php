<?php

use App\Http\Controllers\Finance\AccountsPayableController;
use App\Http\Controllers\Finance\AccountsReceivableController;
use App\Http\Controllers\Finance\ChartOfAccountsController;
use App\Http\Controllers\Finance\JournalEntryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 14: BASIC FINANCE MODULE
| - Chart of Accounts (CRUD, Toggle Status, Hierarchy, Export)
| - Journal Entries (CRUD, Post, Reverse, Export, Print)
| - Accounts Payable (CRUD, Record Payment, Export)
| - Accounts Receivable (CRUD, Record Payment, Aging Report, Send Reminder, Export)
|
| Access: Admin + Finance_HR (Operator = No Access)
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin,finance_hr', 'permission:accounts.manage'])
    ->prefix('finance')
    ->name('finance.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | CHART OF ACCOUNTS
        |----------------------------------------------------------------------
        */
        Route::prefix('chart-of-accounts')->name('chart-of-accounts.')->group(function () {
            Route::get('/', [ChartOfAccountsController::class, 'index'])->name('index');
            Route::get('/create', [ChartOfAccountsController::class, 'create'])->name('create');
            Route::post('/', [ChartOfAccountsController::class, 'store'])->name('store');
            Route::get('/export', [ChartOfAccountsController::class, 'export'])->name('export');
            Route::get('/hierarchy', [ChartOfAccountsController::class, 'hierarchy'])->name('hierarchy');
            Route::get('/{accountCode}', [ChartOfAccountsController::class, 'show'])->name('show');
            Route::get('/{accountCode}/edit', [ChartOfAccountsController::class, 'edit'])->name('edit');
            Route::put('/{accountCode}', [ChartOfAccountsController::class, 'update'])->name('update');
            Route::delete('/{accountCode}', [ChartOfAccountsController::class, 'destroy'])->name('destroy');
            Route::patch('/{accountCode}/toggle-status', [ChartOfAccountsController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        /*
        |----------------------------------------------------------------------
        | JOURNAL ENTRIES
        |----------------------------------------------------------------------
        */
        Route::prefix('journal-entries')->name('journal-entries.')->group(function () {
            Route::get('/', [JournalEntryController::class, 'index'])->name('index');
            Route::get('/create', [JournalEntryController::class, 'create'])->name('create');
            Route::post('/', [JournalEntryController::class, 'store'])->name('store');
            Route::get('/export', [JournalEntryController::class, 'export'])->name('export');
            Route::get('/{journalCode}', [JournalEntryController::class, 'show'])->name('show');
            Route::get('/{journalCode}/edit', [JournalEntryController::class, 'edit'])->name('edit');
            Route::put('/{journalCode}', [JournalEntryController::class, 'update'])->name('update');
            Route::delete('/{journalCode}', [JournalEntryController::class, 'destroy'])->name('destroy');
            Route::get('/{journalCode}/print', [JournalEntryController::class, 'print'])->name('print');
            
            // Status actions
            Route::post('/{journalCode}/post', [JournalEntryController::class, 'post'])->name('post');
            Route::post('/{journalCode}/reverse', [JournalEntryController::class, 'reverse'])->name('reverse');
        });
        
        /*
        |----------------------------------------------------------------------
        | ACCOUNTS PAYABLE
        |----------------------------------------------------------------------
        */
        Route::prefix('accounts-payable')->name('accounts-payable.')->group(function () {
            Route::get('/', [AccountsPayableController::class, 'index'])->name('index');
            Route::get('/create', [AccountsPayableController::class, 'create'])->name('create');
            Route::post('/', [AccountsPayableController::class, 'store'])->name('store');
            Route::get('/export', [AccountsPayableController::class, 'export'])->name('export');
            Route::get('/{apCode}', [AccountsPayableController::class, 'show'])->name('show');
            
            // Payment actions
            Route::post('/{apCode}/record-payment', [AccountsPayableController::class, 'recordPayment'])->name('record-payment');
        });
        
        /*
        |----------------------------------------------------------------------
        | ACCOUNTS RECEIVABLE
        |----------------------------------------------------------------------
        */
        Route::prefix('accounts-receivable')->name('accounts-receivable.')->group(function () {
            Route::get('/', [AccountsReceivableController::class, 'index'])->name('index');
            Route::get('/create', [AccountsReceivableController::class, 'create'])->name('create');
            Route::post('/', [AccountsReceivableController::class, 'store'])->name('store');
            Route::get('/export', [AccountsReceivableController::class, 'export'])->name('export');
            Route::get('/aging-report', [AccountsReceivableController::class, 'agingReport'])->name('aging-report');
            Route::get('/{arCode}', [AccountsReceivableController::class, 'show'])->name('show');
            
            // Payment actions
            Route::post('/{arCode}/record-payment', [AccountsReceivableController::class, 'recordPayment'])->name('record-payment');
            Route::post('/{arCode}/send-reminder', [AccountsReceivableController::class, 'sendReminder'])->name('send-reminder');
        });
    });