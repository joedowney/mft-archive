<?php

use App\Http\Controllers\CitiesController;
use Inertia\Inertia;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandsController;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('images', [ImagesController::class, 'show']);

Route::get('bands', [BandsController::class, 'index']);
Route::get('bands/alpha/{letter}', [BandsController::class, 'alpha']);
Route::get('bands/{slug}', [BandsController::class, 'show']);

Route::get('genres', [GenresController::class, 'index']);
Route::get('genres/{slug}', [GenresController::class, 'show']);

Route::get('cities', [CitiesController::class, 'index']);
Route::get('cities/{slug}', [CitiesController::class, 'show']);
