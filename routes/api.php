<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\LaborTypeController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectPhaseController;
use App\Http\Controllers\Api\QuotationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ==================== Clients ====================
    Route::apiResource('clients', ClientController::class);

    // ==================== Projects ====================
    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/{project}/summary', [ProjectController::class, 'summary']);

    // مراحل المشروع (nested)
    Route::post('projects/{project}/phases', [ProjectPhaseController::class, 'store']);
    Route::get('projects/{project}/phases',  [ProjectPhaseController::class, 'index']);

    // ==================== Phases ====================
    Route::get('phases/{phase}',    [ProjectPhaseController::class, 'show']);
    Route::put('phases/{phase}',    [ProjectPhaseController::class, 'update']);
    Route::patch('phases/{phase}',  [ProjectPhaseController::class, 'update']);
    Route::delete('phases/{phase}', [ProjectPhaseController::class, 'destroy']);
    Route::get('phases/{phase}/cost', [ProjectPhaseController::class, 'cost']);

    // إضافة مواد / عمالة / معدات لمرحلة
    Route::post('phases/{phase}/materials',  [ProjectPhaseController::class, 'storeMaterial']);
    Route::post('phases/{phase}/labor',      [ProjectPhaseController::class, 'storeLabor']);
    Route::post('phases/{phase}/equipment',  [ProjectPhaseController::class, 'storeEquipment']);

    // حذف مواد / عمالة / معدات من مرحلة
    Route::delete('phases/{phase}/materials/{material}', [ProjectPhaseController::class, 'destroyMaterial']);
    Route::delete('phases/{phase}/labor/{labor}',        [ProjectPhaseController::class, 'destroyLabor']);
    Route::delete('phases/{phase}/equipment/{equipment}',[ProjectPhaseController::class, 'destroyEquipment']);

    // ==================== Quotations ====================
    Route::get('projects/{project}/quotations',           [QuotationController::class, 'index']);
    Route::post('projects/{project}/quotations/generate', [QuotationController::class, 'generate']);
    Route::get('quotations/{quotation}',                  [QuotationController::class, 'show']);
    Route::patch('quotations/{quotation}/status',         [QuotationController::class, 'updateStatus']);

    // ==================== Catalogs ====================
    Route::apiResource('materials',   MaterialController::class);
    Route::apiResource('labor-types', LaborTypeController::class);
    Route::apiResource('equipment',   EquipmentController::class);

});