<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\LocationController;

Route::post('login', [AuthController::class, 'login']);
Route::apiResource('txapelketak', TournamentController::class);
Route::apiResource('lokalekuak', LocationController::class);
