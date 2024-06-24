<?php

use App\Http\Controllers\Admin\SongsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AlbumsController;
use App\Http\Controllers\Admin\BandsController;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth.session', 'web'])->group(function() {
    Route::get('/', [DashboardController::class, 'show']);
    Route::get('/bands/alpha/{letter}', [BandsController::class, 'alpha']);

    Route::get('/bands/new', [BandsController::class, 'create']);
    Route::post('/bands/new', [BandsController::class, 'store']);

    Route::get('/bands/{band_id}', [BandsController::class, 'edit']);
    Route::post('/bands/{band_id}/name', [BandsController::class, 'updateName']);
    Route::post('/bands/{band_id}/info', [BandsController::class, 'updateInfo']);
    Route::post('/bands/{band_id}/image', [BandsController::class, 'updateImage']);
    Route::post('/bands/{band_id}/image/delete', [BandsController::class, 'deleteImage']);
    Route::post('/bands/{band_id}/genres', [BandsController::class, 'updateGenres']);
    Route::post('/bands/{band_id}/related-bands', [BandsController::class, 'updateRelatedBands']);

    Route::post('/albums', [AlbumsController::class, 'store']);
    Route::get('/albums/{album_id}', [AlbumsController::class, 'edit']);
    Route::post('/albums/{album_id}', [AlbumsController::class, 'update']);
    Route::post('/albums/{album_id}/image', [AlbumsController::class, 'updateImage']);
    Route::post('/albums/{album_id}/image/delete', [AlbumsController::class, 'deleteImage']);

    Route::get('/albums/{album_id}/songs/new', [SongsController::class, 'create']);
    Route::post('/songs', [SongsController::class, 'store']);
});
