<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Band;
use App\Models\Song;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search()
    {
        if ( ! request('q'))
            return redirect()->to('/');

        $q = request('q');

        $bands = Band::search($q)->take(10)->get();
        $albums = Album::search($q)->take(10)->get();
        $albums->load('band');
        $songs = Song::search($q)->take(20)->get();
        $songs->load('album.band');

        return Inertia::render('Search')
            ->with('q', $q)
            ->with('bands', $bands)
            ->with('albums', $albums)
            ->with('songs', $songs);
    }
}
