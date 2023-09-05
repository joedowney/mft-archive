<?php

use App\Http\Controllers\ImagesController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandsController;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('images', [ImagesController::class, 'show']);

Route::get('bands', [BandsController::class, 'index']);
Route::get('bands/alpha/{letter}', [BandsController::class, 'alpha']);
Route::get('bands/{slug}', [BandsController::class, 'show']);
