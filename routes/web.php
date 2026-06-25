<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Admin\OrderController;



Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');
    Route::get('/category.index', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category.create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category.store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category.edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category.update/{category}', [CategoryController::class, 'update'])
        ->name('category.update');
    Route::delete('/category.delete/{category}', [CategoryController::class, 'destroy'])
        ->name('category.delete');
    Route::resource('products', ProductController::class);
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');
    Route::get(
        '/orders/{order}',
        [OrderController::class, 'show']
    )
        ->name('orders.show');
    Route::put(
        '/orders/{order}/status',
        [OrderController::class, 'updateStatus']
    )
        ->name('orders.status');
        
});

Route::get('login', function () {
    return view('layouts.login');
})->name('login');
Route::get('logout', [UserController::class, 'Adminlogout'])->name('logout');
Route::post('/Adminlogin', [UserController::class, 'AdminLogin'])->name('adminlogin');
