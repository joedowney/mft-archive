<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Support\Facades\Storage;

class SongsController extends Controller
{
    public function play($song_id)
    {
        $song = Song::with('album.band')->findOrFail($song_id);

        $path = $song->album->band->Sort.'/'
            .$song->album->band->Path.'/'
            .$song->FileName;

        $url = Storage::temporaryUrl($path, now()->addHour());

        return redirect()->to($url);
    }

    public function data($song_id)
    {
        return Song::with('album.band')->findOrFail($song_id);
    }
}
