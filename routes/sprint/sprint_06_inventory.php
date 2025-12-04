<?php

use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Inventory\WarehouseLocationController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\StockMovementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 6: INVENTORY & WAREHOUSE MODULE
| - Warehouses (CRUD, Export, Toggle Status)
| - Warehouse Locations (CRUD, Export)
| - Stock Tracking (View, Edit, Low Stock, Valuation)
| - Stock Movements (CRUD, Export)
|
| Access: Admin + Operator (Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| WAREHOUSES
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:warehouse.manage'])
    ->prefix('inventory/warehouses')
    ->name('inventory.warehouses.')
    ->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseController::class, 'create'])->name('create');
        Route::post('/', [WarehouseController::class, 'store'])->name('store');
        Route::get('/export', [WarehouseController::class, 'export'])->name('export');
        Route::get('/{warehouseCode}', [WarehouseController::class, 'show'])->name('show');
        Route::get('/{warehouseCode}/edit', [WarehouseController::class, 'edit'])->name('edit');
        Route::put('/{warehouseCode}', [WarehouseController::class, 'update'])->name('update');
        Route::delete('/{warehouseCode}', [WarehouseController::class, 'destroy'])->name('destroy');
        Route::patch('/{warehouseCode}/toggle-status', [WarehouseController::class, 'toggleStatus'])->name('toggle-status');
    });

/*
|--------------------------------------------------------------------------
| WAREHOUSE LOCATIONS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:warehouse.manage'])
    ->prefix('inventory/locations')
    ->name('inventory.locations.')
    ->group(function () {
        Route::get('/', [WarehouseLocationController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseLocationController::class, 'create'])->name('create');
        Route::post('/', [WarehouseLocationController::class, 'store'])->name('store');
        Route::get('/export', [WarehouseLocationController::class, 'export'])->name('export');
        Route::get('/{locationCode}', [WarehouseLocationController::class, 'show'])->name('show');
        Route::get('/{locationCode}/edit', [WarehouseLocationController::class, 'edit'])->name('edit');
        Route::put('/{locationCode}', [WarehouseLocationController::class, 'update'])->name('update');
        Route::delete('/{locationCode}', [WarehouseLocationController::class, 'destroy'])->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| STOCK TRACKING (Inventory)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:stock.view'])
    ->prefix('inventory/stock')
    ->name('inventory.stock.')
    ->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/export', [InventoryController::class, 'export'])->name('export');
        Route::get('/low-stock', [InventoryController::class, 'lowStockReport'])->name('low-stock');
        Route::get('/valuation', [InventoryController::class, 'valuationReport'])->name('valuation');
        Route::get('/{inventoryId}', [InventoryController::class, 'show'])->name('show');
        Route::get('/{inventoryId}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{inventoryId}', [InventoryController::class, 'update'])->name('update');
    });

/*
|--------------------------------------------------------------------------
| STOCK MOVEMENTS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:warehouse.manage'])
    ->prefix('inventory/movements')
    ->name('inventory.movements.')
    ->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('index');
        Route::get('/create', [StockMovementController::class, 'create'])->name('create');
        Route::post('/', [StockMovementController::class, 'store'])->name('store');
        Route::get('/export', [StockMovementController::class, 'export'])->name('export');
        Route::get('/{movementCode}', [StockMovementController::class, 'show'])->name('show');
        Route::delete('/{movementCode}', [StockMovementController::class, 'destroy'])->name('destroy');
    });