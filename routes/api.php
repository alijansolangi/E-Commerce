<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [UserController::class,'login']);
Route::post('register', [UserController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [UserController::class, 'profile']);
});