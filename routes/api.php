<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\WorkLogController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\KnowledgeNoteController;
use App\Http\Controllers\Api\V1\DashboardController;

// Rute Publik (Belum Login)
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Rute Terproteksi (Wajib Login / Bawa Token)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // CRUD Resources
    Route::apiResource('work-logs', WorkLogController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('knowledge-notes', KnowledgeNoteController::class);
});
