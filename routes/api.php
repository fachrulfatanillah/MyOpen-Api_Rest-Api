<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectIsActiveLogController;
use App\Http\Controllers\ProjectNameLogController;
use App\Http\Controllers\ProjectStatusLogController;
use App\Http\Controllers\TablesOfProjectController;
use App\Http\Controllers\TablesOfProjectsNameLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLevelLogController;
use App\Http\Controllers\UserStatusLogController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Routes Users
Route::apiResource('users', UserController::class)->parameters([
    'users' => 'unicode'
]);

Route::apiResource('user-level-logs', UserLevelLogController::class)
    ->only(['index', 'store']);

Route::apiResource('user-status-logs', UserStatusLogController::class)
    ->only(['index', 'store']);

// Routes project
Route::apiResource('projects', ProjectController::class)->parameters([
    'projects' => 'unicode'
]);

Route::apiResource('project-name-logs', ProjectNameLogController::class)->only(['index', 'store']);

Route::apiResource('project-is-active-logs', ProjectIsActiveLogController::class)->only(['index', 'store']);

Route::apiResource('project-status-logs', ProjectStatusLogController::class)->only(['index', 'store']);

// Tables Of Project
Route::apiResource('tables-of-projects', TablesOfProjectController::class)->parameters([
    'tables-of-projects' => 'unicode'
]);

Route::apiResource('table-name-logs', TablesOfProjectsNameLogController::class)->only(['index', 'store']);

Route::apiResource('table-status-logs', TablesOfProjectsStatusLogController::class)->only(['index', 'store']);
