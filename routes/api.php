<?php

use App\Http\Controllers\WorkoutLogController;
use App\Http\Controllers\WorkoutStatsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/workout-logs',              [WorkoutLogController::class, 'index']);
    Route::post('/workout-logs',             [WorkoutLogController::class, 'store']);
    Route::get('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'show']);
    Route::put('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'update']);
    Route::delete('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'destroy']);

    Route::get('/workout-stats', [WorkoutStatsController::class, 'show']);
});
