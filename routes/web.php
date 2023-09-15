<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BandsController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\AlbumsController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\ImagesController;

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

Route::get('search', [SearchController::class, 'search']);

Route::get('albums/{album_id}/data', [AlbumsController::class, 'data']);
Route::get('songs/{song_id}/play', [SongsController::class, 'play']);
Route::get('bands/{band_id}/data', [BandsController::class, 'data']);
