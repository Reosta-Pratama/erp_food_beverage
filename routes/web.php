<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Show login form
    Route::get('/', [AuthController::class, 'showLogin'])->name('login.show');
    Route::get('/login', [AuthController::class, 'showLogin']);

    // Handle login submission
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // General dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');
            
            Route::resource('users', UserController::class);
            Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::patch('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Operator routes
    Route::middleware('role:operator')
        ->prefix('operator')
        ->name('operator.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('operator.dashboard');
            })->name('dashboard');
    });

    // Finance/HR routes
    Route::middleware('role:finance_hr')
        ->prefix('finance-hr')
        ->name('finance_hr.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('finance_hr.dashboard');
            })->name('dashboard');
    });
});

// Design Web
Route::prefix('template')
    ->name('template.')
    ->group(function () {
        // Basic
        Route::get('/alerts', [TemplateController::class, 'alerts'])->name('alerts');
        Route::get('/badge', [TemplateController::class, 'badge'])->name('badge');
        Route::get('/breadcrumb', [TemplateController::class, 'breadcrumb'])->name('breadcrumb');
        Route::get('/buttonGroup', [TemplateController::class, 'buttonGroup'])->name('buttonGroup');
        Route::get('/buttons', [TemplateController::class, 'buttons'])->name('buttons');
        Route::get('/cards', [TemplateController::class, 'cards'])->name('cards');
        Route::get('/dropdowns', [TemplateController::class, 'dropdowns'])->name('dropdowns');
        Route::get('/imageFigures', [TemplateController::class, 'imageFigures'])->name('imageFigures');
        Route::get('/linkInteraction', [TemplateController::class, 'linkInteraction'])->name('linkInteraction');
        Route::get('/listGroup', [TemplateController::class, 'listGroup'])->name('listGroup');
        Route::get('/navs', [TemplateController::class, 'navs'])->name('navs');
        Route::get('/objectFit', [TemplateController::class, 'objectFit'])->name('objectFit');
        Route::get('/pagination', [TemplateController::class, 'pagination'])->name('pagination');
        Route::get('/popovers', [TemplateController::class, 'popovers'])->name('popovers');
        Route::get('/progress', [TemplateController::class, 'progress'])->name('progress');
        Route::get('/spinners', [TemplateController::class, 'spinners'])->name('spinners');
        Route::get('/toasts', [TemplateController::class, 'toasts'])->name('toasts');
        Route::get('/tooltips', [TemplateController::class, 'tooltips'])->name('tooltips');
        Route::get('/typography', [TemplateController::class, 'typography'])->name('typography');

        // Advanced

        // Authtentication

        // Errors

        // Forms

        // Pages
});