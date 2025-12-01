<?php
use App\Http\Controllers\Inventory\WarehouseController;
use App\Http\Controllers\Inventory\WarehouseLocationController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin,operator')->prefix('inventory')->name('inventory.')->group(function () {
    // Warehouses
    Route::prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->middleware('permission:inventory.read')->name('index');
        Route::get('/create', [WarehouseController::class, 'create'])->middleware('permission:inventory.create')->name('create');
        Route::post('/', [WarehouseController::class, 'store'])->middleware('permission:inventory.create')->name('store');
        Route::get('/export', [WarehouseController::class, 'export'])->middleware('permission:inventory.read')->name('export');
        Route::get('/{warehouseCode}', [WarehouseController::class, 'show'])->middleware('permission:inventory.read')->name('show');
        Route::get('/{warehouseCode}/edit', [WarehouseController::class, 'edit'])->middleware('permission:inventory.update')->name('edit');
        Route::put('/{warehouseCode}', [WarehouseController::class, 'update'])->middleware('permission:inventory.update')->name('update');
        Route::delete('/{warehouseCode}', [WarehouseController::class, 'destroy'])->middleware('permission:inventory.delete')->name('destroy');
        Route::patch('/{warehouseCode}/toggle-status', [WarehouseController::class, 'toggleStatus'])->middleware('permission:inventory.update')->name('toggle-status');
    });
    
    // Warehouse Locations
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', [WarehouseLocationController::class, 'index'])->middleware('permission:inventory.read')->name('index');
        Route::get('/create', [WarehouseLocationController::class, 'create'])->middleware('permission:inventory.create')->name('create');
        Route::post('/', [WarehouseLocationController::class, 'store'])->middleware('permission:inventory.create')->name('store');
        Route::get('/export', [WarehouseLocationController::class, 'export'])->middleware('permission:inventory.read')->name('export');
        Route::get('/{locationCode}', [WarehouseLocationController::class, 'show'])->middleware('permission:inventory.read')->name('show');
        Route::get('/{locationCode}/edit', [WarehouseLocationController::class, 'edit'])->middleware('permission:inventory.update')->name('edit');
        Route::put('/{locationCode}', [WarehouseLocationController::class, 'update'])->middleware('permission:inventory.update')->name('update');
        Route::delete('/{locationCode}', [WarehouseLocationController::class, 'destroy'])->middleware('permission:inventory.delete')->name('destroy');
    });
    
    // Stock Tracking
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->middleware('permission:inventory.read')->name('index');
        Route::get('/export', [InventoryController::class, 'export'])->middleware('permission:inventory.read')->name('export');
        Route::get('/low-stock', [InventoryController::class, 'lowStockReport'])->middleware('permission:inventory.read')->name('low-stock');
        Route::get('/valuation', [InventoryController::class, 'valuationReport'])->middleware('permission:inventory.read')->name('valuation');
        Route::get('/{inventoryId}', [InventoryController::class, 'show'])->middleware('permission:inventory.read')->name('show');
        Route::get('/{inventoryId}/edit', [InventoryController::class, 'edit'])->middleware('permission:inventory.update')->name('edit');
        Route::put('/{inventoryId}', [InventoryController::class, 'update'])->middleware('permission:inventory.update')->name('update');
    });
    
    // Stock Movements
    Route::prefix('movements')->name('movements.')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->middleware('permission:inventory.read')->name('index');
        Route::get('/create', [StockMovementController::class, 'create'])->middleware('permission:inventory.create')->name('create');
        Route::post('/', [StockMovementController::class, 'store'])->middleware('permission:inventory.create')->name('store');
        Route::get('/export', [StockMovementController::class, 'export'])->middleware('permission:inventory.read')->name('export');
        Route::get('/{movementCode}', [StockMovementController::class, 'show'])->middleware('permission:inventory.read')->name('show');
        Route::delete('/{movementCode}', [StockMovementController::class, 'destroy'])->middleware('permission:inventory.delete')->name('destroy');
    });
});