<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;

Route::post('login', [AuthController::class, 'login']);
Route::apiResource('txapelketak', TournamentController::class);
Route::apiResource('lokalekuak', LocationController::class);
Route::post('register', [UserController::class, 'register']);



