<?php

use App\Models\ReservationUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TournamentUserController;
use App\Http\Controllers\ReservationUserController;

//Auth
Route::post('/register', [UserController::class, 'register']);
Route::get('/activate/{id}', [UserController::class, 'activateUser']);
Route::post('/login', [UserController::class, 'login']);

//Users
Route::post('/userStore', [UserController::class, 'store']);
Route::get('/getUser/{id?}', [UserController::class, 'index']);
Route::post('/user/{id}', [UserController::class, 'update']);
Route::patch('/deleteUser/{id}', [UserController::class, 'delete']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
Route::post('/send-email', [UserController::class, 'sendEmail']);
Route::post('upload-image', [UserController::class, 'uploadImage']);

// Location
Route::apiResource('lokalekuak', LocationController::class);
Route::get('/getMap/{id}', [LocationController::class, 'index']);
Route::post('/mapak/{id}', [LocationController::class, 'update']);
Route::delete('/lokalekuakDelete/{id}', [LocationController::class, 'delete']);

// Tournament
Route::apiResource('txapelketak', TournamentController::class);
Route::get('/txapelketak-with-users/{id?}', [TournamentController::class, 'indexWithUsers']);


// TournamentUser
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tournaments/{id}/register', [TournamentUserController::class, 'bookIn']);
});

// Reservation
Route::get('/reservations', [ReservationController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/matches/{id}/users', [ReservationUserController::class, 'addUser']);
});
Route::middleware('auth:sanctum')->post('/reservations', [ReservationController::class, 'store']);
Route::middleware('auth:sanctum')->get('/reservation/reservationUser', [ReservationController::class, 'reservationUser']);

Route::get('/tournaments/popularity', [TournamentController::class, 'getPopularTournaments']);

