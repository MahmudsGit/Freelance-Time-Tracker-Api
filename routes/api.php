<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TimeLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Clients
    Route::apiResource('clients', ClientController::class);

    // Projects
    Route::apiResource('projects', ProjectController::class);

    // Time Logs
    Route::apiResource('time-logs', TimeLogController::class);
    
    Route::get('/export', [ReportController::class, 'export']);
    Route::get('/report', [ReportController::class, 'report']);
});
