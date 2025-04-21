<?php

namespace App\Http\Controllers;

use App\Models\Album;

class AlbumsController extends Controller
{
    public function data($album_id)
    {
        $album = Album::with('band')->with('songs')->find($album_id);

        if (auth()->check()) {
            $album->load('songs.userFavorite');
        }

        return Album::with('band')->with('songs')->find($album_id);
    }
}
