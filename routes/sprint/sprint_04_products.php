<?php
use App\Http\Controllers\Products\ProductCategoryController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Inventory\BOMController;
use App\Http\Controllers\Inventory\RecipeController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin,operator')->group(function () {
    
    // Product Categories & Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index'])->middleware('permission:products.read')->name('index');
            Route::get('/create', [ProductCategoryController::class, 'create'])->middleware('permission:products.create')->name('create');
            Route::post('/', [ProductCategoryController::class, 'store'])->middleware('permission:products.create')->name('store');
            Route::get('/{category}', [ProductCategoryController::class, 'show'])->middleware('permission:products.read')->name('show');
            Route::get('/{category}/edit', [ProductCategoryController::class, 'edit'])->middleware('permission:products.update')->name('edit');
            Route::put('/{category}', [ProductCategoryController::class, 'update'])->middleware('permission:products.update')->name('update');
            Route::delete('/{category}', [ProductCategoryController::class, 'destroy'])->middleware('permission:products.delete')->name('destroy');
        });
        
        Route::get('/', [ProductController::class, 'index'])->middleware('permission:products.read')->name('index');
        Route::get('/create', [ProductController::class, 'create'])->middleware('permission:products.create')->name('create');
        Route::post('/', [ProductController::class, 'store'])->middleware('permission:products.create')->name('store');
        Route::get('/export', [ProductController::class, 'export'])->middleware('permission:products.read')->name('export');
        Route::get('/{product}', [ProductController::class, 'show'])->middleware('permission:products.read')->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->middleware('permission:products.update')->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->middleware('permission:products.update')->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('permission:products.delete')->name('destroy');
        Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->middleware('permission:products.update')->name('toggle-status');
        Route::post('/bulk-update-prices', [ProductController::class, 'bulkUpdatePrices'])->middleware('permission:products.update')->name('bulk-update-prices');
    });
    
    // BOM & Recipe
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::prefix('bom')->name('bom.')->group(function () {
            Route::get('/', [BOMController::class, 'index'])->middleware('permission:inventory.read')->name('index');
            Route::get('/create', [BOMController::class, 'create'])->middleware('permission:inventory.create')->name('create');
            Route::post('/', [BOMController::class, 'store'])->middleware('permission:inventory.create')->name('store');
            Route::get('/export', [BOMController::class, 'export'])->middleware('permission:inventory.read')->name('export');
            Route::get('/{bomCode}', [BOMController::class, 'show'])->middleware('permission:inventory.read')->name('show');
            Route::get('/{bomCode}/edit', [BOMController::class, 'edit'])->middleware('permission:inventory.update')->name('edit');
            Route::put('/{bomCode}', [BOMController::class, 'update'])->middleware('permission:inventory.update')->name('update');
            Route::delete('/{bomCode}', [BOMController::class, 'destroy'])->middleware('permission:inventory.delete')->name('destroy');
        });
        
        Route::prefix('recipes')->name('recipes.')->group(function () {
            Route::get('/', [RecipeController::class, 'index'])->middleware('permission:inventory.read')->name('index');
            Route::get('/create', [RecipeController::class, 'create'])->middleware('permission:inventory.create')->name('create');
            Route::post('/', [RecipeController::class, 'store'])->middleware('permission:inventory.create')->name('store');
            Route::get('/export', [RecipeController::class, 'export'])->middleware('permission:inventory.read')->name('export');
            Route::get('/{recipeCode}', [RecipeController::class, 'show'])->middleware('permission:inventory.read')->name('show');
            Route::get('/{recipeCode}/edit', [RecipeController::class, 'edit'])->middleware('permission:inventory.update')->name('edit');
            Route::put('/{recipeCode}', [RecipeController::class, 'update'])->middleware('permission:inventory.update')->name('update');
            Route::delete('/{recipeCode}', [RecipeController::class, 'destroy'])->middleware('permission:inventory.delete')->name('destroy');
            Route::get('/{recipeCode}/print', [RecipeController::class, 'print'])->middleware('permission:inventory.read')->name('print');
        });
    });
});