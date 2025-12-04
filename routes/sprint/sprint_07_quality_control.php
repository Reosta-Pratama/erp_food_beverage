<?php

use App\Http\Controllers\Inventory\QualityControlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPRINT 7: QUALITY CONTROL MODULE
| - QC Inspection (CRUD, Export, Print Report)
| - QC Parameters (managed within inspection)
| - Batch Approval/Rejection
|
| Access: Admin + Operator (Finance_HR = No Access)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| QUALITY CONTROL INSPECTION
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin,operator', 'permission:quality_control.manage'])
    ->prefix('quality-control/inspections')
    ->name('quality-control.inspections.')
    ->group(function () {
        Route::get('/', [QualityControlController::class, 'index'])->name('index');
        Route::get('/create', [QualityControlController::class, 'create'])->name('create');
        Route::post('/', [QualityControlController::class, 'store'])->name('store');
        Route::get('/export', [QualityControlController::class, 'export'])->name('export');
        Route::get('/{qcCode}', [QualityControlController::class, 'show'])->name('show');
        Route::get('/{qcCode}/edit', [QualityControlController::class, 'edit'])->name('edit');
        Route::put('/{qcCode}', [QualityControlController::class, 'update'])->name('update');
        Route::delete('/{qcCode}', [QualityControlController::class, 'destroy'])->name('destroy');
        Route::get('/{qcCode}/print', [QualityControlController::class, 'print'])->name('print');
        
        // // Batch Approval/Rejection Actions
        Route::post('/{qcCode}/approve-batch', [QualityControlController::class, 'approveBatch'])->name('approve-batch');
        Route::post('/{qcCode}/reject-batch', [QualityControlController::class, 'rejectBatch'])->name('reject-batch');
    });