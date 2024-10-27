<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/create-user', [UserController::class,'store_a_user']);
Route::post('/login', [UserController::class,'login']);
Route::post('/admin-login', [UserController::class,'admin_login']);
Route::post('/admin-register', [UserController::class,'admin_register']);

Route::post('/product', [ProductController::class, 'store']); 
Route::get('/products', [ProductController::class, 'index']); 
Route::get('/products/{id}', [ProductController::class, 'show']); 
Route::put('/products/{id}', [ProductController::class, 'update']); 
Route::delete('/products/{id}', [ProductController::class, 'destroy']); 