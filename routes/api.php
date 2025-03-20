<?php

use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

Route::get('posts/{id}', [PostsController::class, 'get']);
Route::get('posts', [PostsController::class, 'list']);
Route::post('posts', [PostsController::class, 'create']);
Route::post('posts:with-email', [PostsController::class, 'createAndSendEmail']);
Route::post('posts:with-email-async', [PostsController::class, 'createAndSendEmailAsync']);
Route::post('posts:with-email-e-l', [PostsController::class, 'createAndSendEmailEL']);
Route::post('posts:with-email-s', [PostsController::class, 'createAndSendEmailS']);
Route::patch('posts/{id}', [PostsController::class, 'update']);
Route::delete('posts/{id}', [PostsController::class, 'delete']);
