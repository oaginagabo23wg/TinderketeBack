<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TournamentUserController;

// User
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/userStore', [UserController::class, 'store']);
Route::get('/getUser/{id?}', [UserController::class, 'index']);
Route::post('/user/{id}', [UserController::class, 'update']);
Route::patch('/deleteUser/{id}', [UserController::class, 'delete']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
Route::post('/send-email', [UserController::class, 'sendEmail']);

// Location
Route::apiResource('lokalekuak', LocationController::class);

// Tournament
Route::apiResource('txapelketak', TournamentController::class);
Route::get('/txapelketak-with-users/{id?}', [TournamentController::class, 'indexWithUsers']);

// TournamentUser
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tournaments/{id}/register', [TournamentUserController::class, 'bookIn']);
});