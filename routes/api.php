<?php

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::controller(OrganizationController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/organizations/{id}', 'show');
    Route::get('/organizations/building/{building_id}', 'getByBuilding');
    Route::get('/organizations/activity/{activity_id}', 'getByActivity');
    Route::get('/organizations/search/name', 'searchByName');
    Route::get('/organizations/search/activity-tree', 'searchByActivityTree');
    Route::get('/organizations/search/geo/radius', 'getByGeoRadius');
    Route::get('/organizations/search/geo/rectangle', 'getByGeoRectangle');
});

Route::controller(BuildingController::class)->group(function () {
    Route::get('/buildings', 'index');
});
