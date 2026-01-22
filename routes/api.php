<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\MaintenanceRequestController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout']);

    Route::group(['prefix' => 'maintenance/requests'], function () {
        Route::get('/', [MaintenanceRequestController::class, 'index']);
        Route::post('/', [MaintenanceRequestController::class, 'store']);
        Route::get('/{maintenanceRequest}', [MaintenanceRequestController::class, 'show']);
        Route::patch('/{maintenanceRequest}', [MaintenanceRequestController::class, 'update']);
        Route::patch('/{maintenanceRequest}/assign', [MaintenanceRequestController::class, 'assign'])->middleware('role:admin');
        Route::patch('/{maintenanceRequest}/status', [MaintenanceRequestController::class, 'status'])->middleware('role:technician');
    });
});
