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
    Route::delete('/user/{user}/profile/{profile}', [UserProfileController::class,'removeProfile']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::get('/profile', [ProfileController::class,'index']);
    Route::post('/profile', [ProfileController::class,'store']);
    Route::get('/profile/{profile}', [ProfileController::class, 'show']);
    Route::put('/profile/{profile}', [ProfileController::class,'update']);
    Route::delete('/profile/{profile}', [ProfileController::class,'destroy']);
});

Route::middleware('auth:sanctum')->get('auth/user', [AuthController::class, 'user']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

