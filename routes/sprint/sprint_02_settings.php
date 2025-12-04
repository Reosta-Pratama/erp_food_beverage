<?php

use App\Http\Controllers\Settings\CompanyProfileController;
use App\Http\Controllers\Settings\CurrencyController;
use App\Http\Controllers\Settings\TaxRateController;
use App\Http\Controllers\Settings\UnitOfMeasureController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 2: SETTINGS MODULE
| - Company Profile (View, Edit, Update, Delete Logo)
| - Units of Measure (CRUD)
| - Currencies (CRUD, Set Base Currency)
| - Tax Rates (CRUD, Toggle Status)
|
| Access: Admin ONLY (Operator & Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin', 'permission:settings.manage'])
    ->prefix('admin/settings')
    ->name('admin.settings.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | COMPANY PROFILE
        |----------------------------------------------------------------------
        */
        Route::prefix('company-profile')->name('company-profile.')->group(function () {
            Route::get('/', [CompanyProfileController::class, 'index'])->name('index');
            Route::get('/edit', [CompanyProfileController::class, 'edit'])->name('edit');
            Route::put('/', [CompanyProfileController::class, 'update'])->name('update');
            Route::delete('/logo', [CompanyProfileController::class, 'deleteLogo'])->name('delete-logo');
        });
        
        /*
        |----------------------------------------------------------------------
        | UNITS OF MEASURE
        |----------------------------------------------------------------------
        */
        Route::prefix('uom')->name('uom.')->group(function () {
            Route::get('/', [UnitOfMeasureController::class, 'index'])->name('index');
            Route::get('/create', [UnitOfMeasureController::class, 'create'])->name('create');
            Route::post('/', [UnitOfMeasureController::class, 'store'])->name('store');
            Route::get('/{uom}', [UnitOfMeasureController::class, 'show'])->name('show');
            Route::get('/{uom}/edit', [UnitOfMeasureController::class, 'edit'])->name('edit');
            Route::put('/{uom}', [UnitOfMeasureController::class, 'update'])->name('update');
            Route::delete('/{uom}', [UnitOfMeasureController::class, 'destroy'])->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | CURRENCIES
        |----------------------------------------------------------------------
        */
        Route::prefix('currencies')->name('currencies.')->group(function () {
            Route::get('/', [CurrencyController::class, 'index'])->name('index');
            Route::get('/create', [CurrencyController::class, 'create'])->name('create');
            Route::post('/', [CurrencyController::class, 'store'])->name('store');
            Route::get('/{currency}', [CurrencyController::class, 'show'])->name('show');
            Route::get('/{currency}/edit', [CurrencyController::class, 'edit'])->name('edit');
            Route::put('/{currency}', [CurrencyController::class, 'update'])->name('update');
            Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('destroy');
            Route::patch('/{currency}/set-base', [CurrencyController::class, 'setBase'])->name('set-base');
        });
        
        /*
        |----------------------------------------------------------------------
        | TAX RATES
        |----------------------------------------------------------------------
        */
        Route::prefix('tax-rates')->name('tax-rates.')->group(function () {
            Route::get('/', [TaxRateController::class, 'index'])->name('index');
            Route::get('/create', [TaxRateController::class, 'create'])->name('create');
            Route::post('/', [TaxRateController::class, 'store'])->name('store');
            Route::get('/{taxRate}', [TaxRateController::class, 'show'])->name('show');
            Route::get('/{taxRate}/edit', [TaxRateController::class, 'edit'])->name('edit');
            Route::put('/{taxRate}', [TaxRateController::class, 'update'])->name('update');
            Route::delete('/{taxRate}', [TaxRateController::class, 'destroy'])->name('destroy');
            Route::patch('/{taxRate}/toggle-status', [TaxRateController::class, 'toggleStatus'])->name('toggle-status');
        });
    });