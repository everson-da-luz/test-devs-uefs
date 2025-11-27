<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('api.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('posts/tag', [PostController::class, 'postTag']);
    Route::delete('posts/tag', [PostController::class, 'deleteTag']);

    Route::apiResource('posts', PostController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('tags', TagController::class);
});
