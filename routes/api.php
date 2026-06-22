<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Flight\FlightController;
use App\Http\Controllers\Trains\TrainController;



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::get('flights/search', [FlightController::class, 'search']);
    Route::get('flights/{id}', [FlightController::class, 'show']);


    Route::get('/transport/search', [TrainController::class, 'searchByIata']);
    Route::get('trains/{id}', [TrainController::class, 'show']);

});

