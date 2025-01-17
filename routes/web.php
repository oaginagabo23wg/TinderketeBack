<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('txapelketak', TournamentController::class);
Route::resource('register', UserController::class);