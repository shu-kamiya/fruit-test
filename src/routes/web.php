<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/register', [ProductController::class, 'create']);
Route::post('/products/confirm', [ProductController::class, 'confirm']);
Route::post('/products/register', [ProductController::class, 'store']);
Route::get('/products/{productId}', [ProductController::class, 'show']);
Route::get('/products/{productId}/edit', [ProductController::class, 'edit']);
Route::post('/products/{productId}/update', [ProductController::class, 'update']);
Route::post('/products/{productId}/delete', [ProductController::class, 'destroy']);
Route::get('/products/search', [ProductController::class, 'search']);