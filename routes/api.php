<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\DB;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

// Route::get('products',[ProductController::class,'index'])->name('products.index');
// Route::get('products/{id}',[ProductController::class,'show'])->name('products.show');

Route::get('/products', [ProductController::class, 'index'])->name('products');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [UserController::class, 'profile']);

    });

// Cart Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/cart/add', [CartController::class, 'add']);
    
    Route::get('/cart', [CartController::class, 'index']);


    Route::patch('/cart/update/{id}', [CartController::class, 'updateQuantity']);

    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);

    Route::post('/checkout', [OrderController::class, 'checkout']);
    
    Route::get('/orders', [OrderController::class, 'index']);
    
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});
