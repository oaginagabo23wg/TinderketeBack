<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/getUser/{id?}', [UserController::class, 'index']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::patch('/deleteUser/{id}', [UserController::class, 'delete']); 
Route::apiResource('txapelketak', TournamentController::class);
Route::get('/txapelketak-with-users/{id?}', [TournamentController::class, 'indexWithUsers']);
Route::apiResource('lokalekuak', LocationController::class);
Route::get('/getMap/{id}', [LocationController::class, 'index']);
Route::put('/mapak/{id}', [LocationController::class, 'update']);
Route::patch('/lokalekuakDelete/{id}', [LocationController::class, 'delete']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);






