<?php

use App\Http\Controllers\Products\ProductCategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Inventory\BOMController;
use App\Http\Controllers\Inventory\RecipeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 4: PRODUCTS & BOM/RECIPE MODULE
| - Product Categories (CRUD)
| - Products (CRUD, Export, Toggle Status, Bulk Update Prices)
| - Bill of Materials (CRUD, Export)
| - Recipes (CRUD, Export, Print)
|
| Access: Admin + Operator (Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PRODUCT CATEGORIES & PRODUCTS
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:products.manage'])
    ->prefix('products')
    ->name('products.')
    ->group(function () {
        
        /*
        |----------------------------------------------------------------------
        | PRODUCT CATEGORIES
        |----------------------------------------------------------------------
        */
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
            Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
            Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
            Route::get('/{category}', [ProductCategoryController::class, 'show'])->name('show');
            Route::get('/{category}/edit', [ProductCategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [ProductCategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [ProductCategoryController::class, 'destroy'])->name('destroy');
        });
        
        /*
        |----------------------------------------------------------------------
        | PRODUCTS
        |----------------------------------------------------------------------
        */
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/export', [ProductController::class, 'export'])->name('export');
        Route::post('/bulk-update-prices', [ProductController::class, 'bulkUpdatePrices'])->name('bulk-update-prices');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
    });

/*
|--------------------------------------------------------------------------
| BOM (BILL OF MATERIALS)
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:bom.manage'])
    ->prefix('inventory/bom')
    ->name('inventory.bom.')
    ->group(function () {
        Route::get('/', [BOMController::class, 'index'])->name('index');
        Route::get('/create', [BOMController::class, 'create'])->name('create');
        Route::post('/', [BOMController::class, 'store'])->name('store');
        Route::get('/export', [BOMController::class, 'export'])->name('export');
        Route::get('/{bomCode}', [BOMController::class, 'show'])->name('show');
        Route::get('/{bomCode}/edit', [BOMController::class, 'edit'])->name('edit');
        Route::put('/{bomCode}', [BOMController::class, 'update'])->name('update');
        Route::delete('/{bomCode}', [BOMController::class, 'destroy'])->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| RECIPES
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:bom.manage'])
    ->prefix('inventory/recipes')
    ->name('inventory.recipes.')
    ->group(function () {
        Route::get('/', [RecipeController::class, 'index'])->name('index');
        Route::get('/create', [RecipeController::class, 'create'])->name('create');
        Route::post('/', [RecipeController::class, 'store'])->name('store');
        Route::get('/export', [RecipeController::class, 'export'])->name('export');
        Route::get('/{recipeCode}', [RecipeController::class, 'show'])->name('show');
        Route::get('/{recipeCode}/edit', [RecipeController::class, 'edit'])->name('edit');
        Route::put('/{recipeCode}', [RecipeController::class, 'update'])->name('update');
        Route::delete('/{recipeCode}', [RecipeController::class, 'destroy'])->name('destroy');
        Route::get('/{recipeCode}/print', [RecipeController::class, 'print'])->name('print');
    });