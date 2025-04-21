<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FavoritesController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites()
            ->with('song.album.band')
            ->latest()
            ->get();

        return Inertia::render('Favorites/Index', [
            'songs' => $favorites->pluck('song')
        ]);
    }

    public function update()
    {
        $this->validate(request(), [
            'song_id' => 'required',
            'state' => Rule::in(['on', 'off'])
        ]);

        if (request('state') === 'on') {
            auth()->user()->favorites()->firstOrCreate([
                'song_id' => request('song_id')
            ]);
        }

        if (request('state') === 'off') {
            auth()->user()->favorites()->where('song_id', request('song_id'))->delete();
        }
    }
}
