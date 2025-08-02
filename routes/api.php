<?php

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Support\Facades\Route;

Route::controller(OrganizationController::class)->prefix('organizations')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', 'show');
    Route::get('/building/{building_id}', 'getByBuilding');
    Route::get('/activity/{activity_id}', 'getByActivity');
    Route::get('/search/name', 'searchByName');
    Route::post('/search/activity/tree', 'searchByActivityTree');
    Route::post('/search/geo/radius', 'getByGeoRadius');
    Route::post('/search/geo/rectangle', 'getByGeoRectangle');
});

Route::controller(BuildingController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/buildings', 'index');
});

Route::get('/token', [TokenController::class, 'getToken']);
