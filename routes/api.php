<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Rutas para autenticación con Sanctum
Route::post('login', [AuthController::class, 'login']);

// Rutas para usuarios protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class); // Usamos apiResource para gestionar las rutas de usuarios
});

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

