<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use Illuminate\Support\Facades\Route;

Route::post('posts/tag', [PostController::class, 'postTag']);
Route::delete('posts/tag', [PostController::class, 'deleteTag']);

Route::apiResource('users', UserController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('tags', TagController::class);
