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
| Access: Admin Only
|--------------------------------------------------------------------------
*/

Route::middleware('role:admin')
    ->prefix('admin/settings')
    ->name('admin.settings.')
    ->group(function () {
        
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
