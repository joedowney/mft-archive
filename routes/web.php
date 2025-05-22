<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\BandsController;
use App\Http\Controllers\AlbumsController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\FavoritesController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('dashboard');

Route::get('images', [ImagesController::class, 'show']);

Route::get('bands', [BandsController::class, 'index']);
Route::get('bands/alpha/{letter}', [BandsController::class, 'alpha']);
Route::get('bands/{slug}', [BandsController::class, 'show']);
Route::get('bands/{band_id}/data', [BandsController::class, 'data']);

Route::get('albums/{album_id}/data', [AlbumsController::class, 'data']);

Route::get('songs/{song_id}/play', [SongsController::class, 'play']);
Route::get('songs/{song_id}/data', [SongsController::class, 'data']);

Route::get('genres', [GenresController::class, 'index']);
Route::get('genres/{slug}', [GenresController::class, 'show']);

Route::get('cities', [CitiesController::class, 'index']);
Route::get('cities/{slug}', [CitiesController::class, 'show']);

Route::get('search', [SearchController::class, 'search']);

// Public playlist routes
Route::get('playlists/dropdown', [PlaylistsController::class, 'listForDropdown'])->name('playlists.dropdown');
Route::get('playlists/{playlist}', [PlaylistsController::class, 'show'])->name('playlists.show');

Route::middleware(['auth'])->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('favorite', [FavoritesController::class, 'update'])->name('favorite.update');
    Route::get('favorites', [FavoritesController::class, 'index'])->name('favorites.index');

    // Playlist routes
    Route::get('playlists', [PlaylistsController::class, 'index'])->name('playlists.index');
    Route::get('playlists/create', [PlaylistsController::class, 'create'])->name('playlists.create');
    Route::post('playlists', [PlaylistsController::class, 'store'])->name('playlists.store');
    Route::get('playlists/{playlist}/edit', [PlaylistsController::class, 'edit'])->name('playlists.edit');
    Route::patch('playlists/{playlist}', [PlaylistsController::class, 'update'])->name('playlists.update');
    Route::delete('playlists/{playlist}', [PlaylistsController::class, 'destroy'])->name('playlists.destroy');

    // Playlist songs management
    Route::post('playlists/{playlist}/songs', [PlaylistsController::class, 'addSong'])->name('playlists.songs.add');
    Route::delete('playlists/{playlist}/songs', [PlaylistsController::class, 'removeSong'])->name('playlists.songs.remove');
    Route::put('playlists/{playlist}/songs/reorder', [PlaylistsController::class, 'reorderSongs'])->name('playlists.songs.reorder');
});

require __DIR__.'/auth.php';

require __DIR__.'/admin.php';
