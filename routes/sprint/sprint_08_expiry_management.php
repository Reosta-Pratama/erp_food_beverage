<?php

use App\Http\Controllers\Inventory\ExpiryTrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 8: EXPIRY MANAGEMENT MODULE
| - Expiry Tracking (CRUD, Export, Alert Management)
| - Expiry Alerts & Notifications
| - Critical Alerts (Near Expiry, Expired)
|
| Access: Admin + Operator (Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| EXPIRY TRACKING
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:warehouse.manage'])
    ->prefix('inventory/expiry-tracking')
    ->name('inventory.expiry-tracking.')
    ->group(function () {
        Route::get('/', [ExpiryTrackingController::class, 'index'])->name('index');
        Route::get('/create', [ExpiryTrackingController::class, 'create'])->name('create');
        Route::post('/', [ExpiryTrackingController::class, 'store'])->name('store');
        Route::get('/export', [ExpiryTrackingController::class, 'export'])->name('export');
        Route::get('/alerts', [ExpiryTrackingController::class, 'alerts'])->name('alerts');
        Route::get('/critical-alerts', [ExpiryTrackingController::class, 'criticalAlerts'])->name('critical-alerts');
        Route::get('/near-expiry', [ExpiryTrackingController::class, 'nearExpiry'])->name('near-expiry');
        Route::get('/expired', [ExpiryTrackingController::class, 'expired'])->name('expired');
        Route::get('/{expiryId}', [ExpiryTrackingController::class, 'show'])->name('show');
        Route::get('/{expiryId}/edit', [ExpiryTrackingController::class, 'edit'])->name('edit');
        Route::put('/{expiryId}', [ExpiryTrackingController::class, 'update'])->name('update');
        Route::delete('/{expiryId}', [ExpiryTrackingController::class, 'destroy'])->name('destroy');
        
        // Bulk actions
        Route::post('/bulk-dispose', [ExpiryTrackingController::class, 'bulkDispose'])->name('bulk-dispose');
        Route::post('/bulk-alert', [ExpiryTrackingController::class, 'bulkSetAlert'])->name('bulk-alert');
    });