<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;

// Rutas para autenticación con Sanctum
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('txapelketak', TournamentController::class);
Route::apiResource('lokalekuak', LocationController::class);

// Rutas para usuarios protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class); // Usamos apiResource para gestionar las rutas de usuarios
});

