<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MediaController;

// PUBLIC routes (no auth required)
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/tags', [TagController::class, 'index']);

// PROTECTED routes (require auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Post management
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Category management
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);

    // Tag management
    Route::apiResource('tags', TagController::class)->except(['index', 'show']);

    // Project management
    Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);

    // Media upload
    Route::post('/upload', [MediaController::class, 'upload']);
});