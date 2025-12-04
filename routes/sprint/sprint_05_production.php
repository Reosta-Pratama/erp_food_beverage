<?php

use App\Http\Controllers\Production\ProductionPlanningController;
use App\Http\Controllers\Production\WorkOrderController;
use App\Http\Controllers\Production\BatchController;
use App\Http\Controllers\Inventory\LotController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 5: PRODUCTION MANAGEMENT MODULE
| - Production Planning (CRUD, Export, Approve)
| - Work Orders (CRUD, Export, Print, Start, Complete, Cancel)
| - Batches (CRUD, Export, Print Label)
| - Lot Tracking (CRUD, Export)
|
| Access: Admin + Operator (Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PRODUCTION PLANNING
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:production_planning.manage'])
    ->prefix('production/planning')
    ->name('production.planning.')
    ->group(function () {
        Route::get('/', [ProductionPlanningController::class, 'index'])->name('index');
        Route::get('/create', [ProductionPlanningController::class, 'create'])->name('create');
        Route::post('/', [ProductionPlanningController::class, 'store'])->name('store');
        Route::get('/export', [ProductionPlanningController::class, 'export'])->name('export');
        Route::get('/{planCode}', [ProductionPlanningController::class, 'show'])->name('show');
        Route::get('/{planCode}/edit', [ProductionPlanningController::class, 'edit'])->name('edit');
        Route::put('/{planCode}', [ProductionPlanningController::class, 'update'])->name('update');
        Route::delete('/{planCode}', [ProductionPlanningController::class, 'destroy'])->name('destroy');
        Route::post('/{planCode}/approve', [ProductionPlanningController::class, 'approve'])->name('approve');
    });

/*
|--------------------------------------------------------------------------
| WORK ORDERS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:work_orders.manage'])
    ->prefix('production/work-orders')
    ->name('production.work-orders.')
    ->group(function () {
        Route::get('/', [WorkOrderController::class, 'index'])->name('index');
        Route::get('/create', [WorkOrderController::class, 'create'])->name('create');
        Route::post('/', [WorkOrderController::class, 'store'])->name('store');
        Route::get('/export', [WorkOrderController::class, 'export'])->name('export');
        Route::get('/{woCode}', [WorkOrderController::class, 'show'])->name('show');
        Route::get('/{woCode}/edit', [WorkOrderController::class, 'edit'])->name('edit');
        Route::put('/{woCode}', [WorkOrderController::class, 'update'])->name('update');
        Route::delete('/{woCode}', [WorkOrderController::class, 'destroy'])->name('destroy');
        Route::post('/{woCode}/start', [WorkOrderController::class, 'start'])->name('start');
        Route::post('/{woCode}/complete', [WorkOrderController::class, 'complete'])->name('complete');
        Route::post('/{woCode}/cancel', [WorkOrderController::class, 'cancel'])->name('cancel');
        Route::get('/{woCode}/print', [WorkOrderController::class, 'print'])->name('print');
    });

/*
|--------------------------------------------------------------------------
| BATCHES
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:work_orders.manage'])
    ->prefix('production/batches')
    ->name('production.batches.')
    ->group(function () {
        Route::get('/', [BatchController::class, 'index'])->name('index');
        Route::get('/create', [BatchController::class, 'create'])->name('create');
        Route::post('/', [BatchController::class, 'store'])->name('store');
        Route::get('/export', [BatchController::class, 'export'])->name('export');
        Route::get('/{batchCode}', [BatchController::class, 'show'])->name('show');
        Route::get('/{batchCode}/edit', [BatchController::class, 'edit'])->name('edit');
        Route::put('/{batchCode}', [BatchController::class, 'update'])->name('update');
        Route::delete('/{batchCode}', [BatchController::class, 'destroy'])->name('destroy');
        Route::get('/{batchCode}/print-label', [BatchController::class, 'printLabel'])->name('print-label');
    });

/*
|--------------------------------------------------------------------------
| LOT TRACKING
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:work_orders.manage'])
    ->prefix('inventory/lots')
    ->name('inventory.lots.')
    ->group(function () {
        Route::get('/', [LotController::class, 'index'])->name('index');
        Route::get('/create', [LotController::class, 'create'])->name('create');
        Route::post('/', [LotController::class, 'store'])->name('store');
        Route::get('/export', [LotController::class, 'export'])->name('export');
        Route::get('/{lotCode}', [LotController::class, 'show'])->name('show');
        Route::get('/{lotCode}/edit', [LotController::class, 'edit'])->name('edit');
        Route::put('/{lotCode}', [LotController::class, 'update'])->name('update');
        Route::delete('/{lotCode}', [LotController::class, 'destroy'])->name('destroy');
    });