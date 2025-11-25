<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\BookingController;

Route::get('/', [MovieController::class, 'index']);
Route::get('/movie/{id}', [MovieController::class, 'show']);

Route::get('/showtime/{id}', [ShowtimeController::class, 'show']);
Route::post('/booking', [BookingController::class, 'store']);
