<?php

use App\Http\Controllers\Logistics\DeliveryController;
use App\Http\Controllers\Logistics\FleetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 11: DELIVERY & DISTRIBUTION MODULE
| - Delivery Management (CRUD, Export, Print, Confirm)
| - Delivery Confirmation
| - Basic Fleet Tracking (Drivers, Vehicles)
|
| Access: Admin + Operator (Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| DELIVERY MANAGEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:delivery.manage'])
    ->prefix('logistics/deliveries')
    ->name('logistics.deliveries.')
    ->group(function () {
        Route::get('/', [DeliveryController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryController::class, 'create'])->name('create');
        Route::post('/', [DeliveryController::class, 'store'])->name('store');
        Route::get('/export', [DeliveryController::class, 'export'])->name('export');
        Route::get('/{deliveryCode}', [DeliveryController::class, 'show'])->name('show');
        Route::get('/{deliveryCode}/edit', [DeliveryController::class, 'edit'])->name('edit');
        Route::put('/{deliveryCode}', [DeliveryController::class, 'update'])->name('update');
        Route::delete('/{deliveryCode}', [DeliveryController::class, 'destroy'])->name('destroy');
        Route::get('/{deliveryCode}/print', [DeliveryController::class, 'print'])->name('print');
        
        // Status actions
        Route::post('/{deliveryCode}/dispatch', [DeliveryController::class, 'dispatch'])->name('dispatch');
        Route::post('/{deliveryCode}/in-transit', [DeliveryController::class, 'inTransit'])->name('in-transit');
        Route::post('/{deliveryCode}/complete', [DeliveryController::class, 'complete'])->name('complete');
        Route::post('/{deliveryCode}/confirm', [DeliveryController::class, 'confirm'])->name('confirm');
    });

/*
|--------------------------------------------------------------------------
| FLEET MANAGEMENT (BASIC)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:logistics.manage'])
    ->prefix('logistics/fleet')
    ->name('logistics.fleet.')
    ->group(function () {
        // Drivers
        Route::prefix('drivers')->name('drivers.')->group(function () {
            Route::get('/', [FleetController::class, 'driversIndex'])->name('index');
            Route::get('/create', [FleetController::class, 'driversCreate'])->name('create');
            Route::post('/', [FleetController::class, 'driversStore'])->name('store');
            Route::get('/{driverId}/edit', [FleetController::class, 'driversEdit'])->name('edit');
            Route::put('/{driverId}', [FleetController::class, 'driversUpdate'])->name('update');
            Route::delete('/{driverId}', [FleetController::class, 'driversDestroy'])->name('destroy');
            Route::patch('/{driverId}/toggle-availability', [FleetController::class, 'toggleDriverAvailability'])->name('toggle-availability');
        });
        
        // Vehicles
        Route::prefix('vehicles')->name('vehicles.')->group(function () {
            Route::get('/', [FleetController::class, 'vehiclesIndex'])->name('index');
            Route::get('/create', [FleetController::class, 'vehiclesCreate'])->name('create');
            Route::post('/', [FleetController::class, 'vehiclesStore'])->name('store');
            Route::get('/{vehicleCode}/edit', [FleetController::class, 'vehiclesEdit'])->name('edit');
            Route::put('/{vehicleCode}', [FleetController::class, 'vehiclesUpdate'])->name('update');
            Route::delete('/{vehicleCode}', [FleetController::class, 'vehiclesDestroy'])->name('destroy');
        });
    });