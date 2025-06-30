<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;

Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);
    Route::post('/user/{user}/profile', [UserProfileController::class,'addProfile']);
    Route::post('/user/{user}/profile', [UserProfileController::class,'removeProfile']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::get('/profile', [ProfileController::class,'index']);
    Route::post('/profile', [ProfileController::class,'store']);
    Route::get('/profile/{profile}', [ProfileController::class, 'show']);
    Route::put('/profile/{profile}', [ProfileController::class,'update']);
    Route::delete('/profile/{profile}', [ProfileController::class,'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
