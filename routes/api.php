<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {

    Route::prefix('products')->group(function () {
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/{id}', [OrderController::class, 'show']);
            Route::post('create', [OrderController::class, 'create']);
        });

        Route::prefix('cart')->group(function () {
            Route::get('/',[ShoppingCartController::class, 'index']);
            Route::post('/add',[ShoppingCartController::class, 'add']);
            Route::put('/update/{CartItemId}',[ShoppingCartController::class, 'update']);
            Route::delete('/remove/{CartItemId}',[ShoppingCartController::class, 'remove']);
        });

    });
});
