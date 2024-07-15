<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function() {
    Route::get('get-users', 'index')->middleware('auth:sanctum');
    Route::get('profile', 'profile')->middleware('auth:sanctum');
    // Route::patch('profile-update', 'update');
    // Route::post('store', 'store');
});
