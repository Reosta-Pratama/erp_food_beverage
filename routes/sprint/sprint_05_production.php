<?php
use App\Http\Controllers\Production\ProductionPlanningController;
use App\Http\Controllers\Production\WorkOrderController;
use App\Http\Controllers\Production\BatchController;
use App\Http\Controllers\Inventory\LotController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin,operator')->prefix('production')->name('production.')->group(function () {
    // Production Planning
    Route::prefix('planning')->name('planning.')->group(function () {
        Route::get('/', [ProductionPlanningController::class, 'index'])->middleware('permission:production.read')->name('index');
        Route::get('/create', [ProductionPlanningController::class, 'create'])->middleware('permission:production.create')->name('create');
        Route::post('/', [ProductionPlanningController::class, 'store'])->middleware('permission:production.create')->name('store');
        Route::get('/export', [ProductionPlanningController::class, 'export'])->middleware('permission:production.read')->name('export');
        Route::get('/{planCode}', [ProductionPlanningController::class, 'show'])->middleware('permission:production.read')->name('show');
        Route::get('/{planCode}/edit', [ProductionPlanningController::class, 'edit'])->middleware('permission:production.update')->name('edit');
        Route::put('/{planCode}', [ProductionPlanningController::class, 'update'])->middleware('permission:production.update')->name('update');
        Route::delete('/{planCode}', [ProductionPlanningController::class, 'destroy'])->middleware('permission:production.delete')->name('destroy');
        Route::post('/{planCode}/approve', [ProductionPlanningController::class, 'approve'])->middleware('permission:production.update')->name('approve');
    });
    
    // Work Orders
    Route::prefix('work-orders')->name('work-orders.')->group(function () {
        Route::get('/', [WorkOrderController::class, 'index'])->middleware('permission:production.read')->name('index');
        Route::get('/create', [WorkOrderController::class, 'create'])->middleware('permission:production.create')->name('create');
        Route::post('/', [WorkOrderController::class, 'store'])->middleware('permission:production.create')->name('store');
        Route::get('/export', [WorkOrderController::class, 'export'])->middleware('permission:production.read')->name('export');
        Route::get('/{woCode}', [WorkOrderController::class, 'show'])->middleware('permission:production.read')->name('show');
        Route::get('/{woCode}/edit', [WorkOrderController::class, 'edit'])->middleware('permission:production.update')->name('edit');
        Route::put('/{woCode}', [WorkOrderController::class, 'update'])->middleware('permission:production.update')->name('update');
        Route::delete('/{woCode}', [WorkOrderController::class, 'destroy'])->middleware('permission:production.delete')->name('destroy');
        Route::post('/{woCode}/start', [WorkOrderController::class, 'start'])->middleware('permission:production.update')->name('start');
        Route::post('/{woCode}/complete', [WorkOrderController::class, 'complete'])->middleware('permission:production.update')->name('complete');
        Route::post('/{woCode}/cancel', [WorkOrderController::class, 'cancel'])->middleware('permission:production.update')->name('cancel');
        Route::get('/{woCode}/print', [WorkOrderController::class, 'print'])->middleware('permission:production.read')->name('print');
    });
    
    // Batches
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [BatchController::class, 'index'])->middleware('permission:production.read')->name('index');
        Route::get('/create', [BatchController::class, 'create'])->middleware('permission:production.create')->name('create');
        Route::post('/', [BatchController::class, 'store'])->middleware('permission:production.create')->name('store');
        Route::get('/export', [BatchController::class, 'export'])->middleware('permission:production.read')->name('export');
        Route::get('/{batchCode}', [BatchController::class, 'show'])->middleware('permission:production.read')->name('show');
        Route::get('/{batchCode}/edit', [BatchController::class, 'edit'])->middleware('permission:production.update')->name('edit');
        Route::put('/{batchCode}', [BatchController::class, 'update'])->middleware('permission:production.update')->name('update');
        Route::delete('/{batchCode}', [BatchController::class, 'destroy'])->middleware('permission:production.delete')->name('destroy');
        Route::get('/{batchCode}/print-label', [BatchController::class, 'printLabel'])->middleware('permission:production.read')->name('print-label');
    });
});

// Lot Tracking
Route::middleware('role:admin,operator')->prefix('inventory/lots')->name('inventory.lots.')->group(function () {
    Route::get('/', [LotController::class, 'index'])->middleware('permission:inventory.read')->name('index');
    Route::get('/create', [LotController::class, 'create'])->middleware('permission:inventory.create')->name('create');
    Route::post('/', [LotController::class, 'store'])->middleware('permission:inventory.create')->name('store');
    Route::get('/export', [LotController::class, 'export'])->middleware('permission:inventory.read')->name('export');
    Route::get('/{lotCode}', [LotController::class, 'show'])->middleware('permission:inventory.read')->name('show');
    Route::get('/{lotCode}/edit', [LotController::class, 'edit'])->middleware('permission:inventory.update')->name('edit');
    Route::put('/{lotCode}', [LotController::class, 'update'])->middleware('permission:inventory.update')->name('update');
    Route::delete('/{lotCode}', [LotController::class, 'destroy'])->middleware('permission:inventory.delete')->name('destroy');
});