<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLevelLogController;
use App\Http\Controllers\UserStatusLogController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('users', UserController::class)->parameters([
    'users' => 'unicode'
]);

Route::apiResource('user-level-logs', UserLevelLogController::class)
    ->only(['index', 'store']);

Route::apiResource('user-status-logs', UserStatusLogController::class)
    ->only(['index', 'store']);
