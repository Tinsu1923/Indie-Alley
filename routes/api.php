<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/create-user', [UserController::class,'store_a_user']);
Route::post('/login', [UserController::class,'login']);
Route::post('/admin-login', [UserController::class,'admin_login']);
Route::post('/admin-register', [UserController::class,'admin_register']);

