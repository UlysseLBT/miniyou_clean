<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('posts', PostController::class);
    Route::apiResource('users', UserController::class);

    // ─── ADMIN ───────────────────────────────────────────────────────────────────
    Route::middleware('is_admin')->prefix('admin')->group(function () {

        // Tableau de bord
        Route::get('/stats',                        [AdminController::class, 'stats']);

        // Utilisateurs
        Route::get('/users',                        [AdminController::class, 'users']);
        Route::post('/users/{id}/ban',              [AdminController::class, 'banUser']);
        Route::post('/users/{id}/unban',            [AdminController::class, 'unbanUser']);
        Route::delete('/users/{id}',                [AdminController::class, 'deleteUser']);
        Route::post('/users/{id}/promote',          [AdminController::class, 'promoteUser']);

        // Posts
        Route::get('/posts',                        [AdminController::class, 'posts']);
        Route::delete('/posts/{id}',                [AdminController::class, 'deletePost']);

        // Signalements
        Route::get('/reports',                      [AdminController::class, 'reports']);
        Route::post('/reports/{id}/resolve',        [AdminController::class, 'resolveReport']);
        Route::post('/reports/{id}/dismiss',        [AdminController::class, 'dismissReport']);
        Route::post('/reports/{id}/resolve-delete', [AdminController::class, 'resolveAndDelete']);
    });
});