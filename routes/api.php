<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/customer')->group(function () {
    Route::post('/register', [App\Http\Controllers\Customer\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Customer\AuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Customer\AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
    Route::get('/profile', [App\Http\Controllers\Customer\AuthController::class, 'profile'])
        ->middleware('auth:sanctum');
});

Route::prefix('/home')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\HomeDataController::class, 'index']);
})->middleware('auth:sanctum');

Route::prefix('/products')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\ProductsController::class, 'index']);
    Route::get('/category', [App\Http\Controllers\Customer\ProductsController::class, 'getByCategory']);
    Route::get('/brand', [App\Http\Controllers\Customer\ProductsController::class, 'getByBrand']);
    Route::get('/product', [App\Http\Controllers\Customer\ProductsController::class, 'getByProducts']);
});
