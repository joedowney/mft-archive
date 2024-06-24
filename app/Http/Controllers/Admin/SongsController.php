<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Song;
use App\Support\MP3File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SongsController extends Controller
{
    public function create($album_id)
    {
        $album = Album::findOrFail($album_id);

        return Inertia::render('Admin/Songs/Create')->with('album', $album);
    }

    public function store()
    {
        $this->validate(request(), [
            'album_id' => 'required',
            'title' => '',
            'file' => 'required|file'
        ]);

        $album = Album::findOrFail(request('album_id'));

        $file = request()->file('file');
        $filename = $file->getClientOriginalName();
        $mp3 = new MP3File($file->getPathname());
        $seconds = $mp3->getDuration();
        $duration = (floor($seconds/60) % 60) . ':' . str_pad($seconds % 60, 2, '0', STR_PAD_LEFT);
        $file_path = $album->band->Sort.'/'
            . $album->band->Path.'/'
            . $filename;
        $file->storeAs($file_path);

        $title = request('title') ?: pathinfo($filename, PATHINFO_EXTENSION);
        $ord = (optional($album->songs()->orderBy('Ord', 'DESC')->first())->Ord ?? 0) + 1;
        $id = Song::orderBy('ID', 'DESC')->limit(1)->first('ID')->ID + 1;

        Song::create([
            'ID' => $id,
            'AlbumID' => $album->ID,
            'BandID' => $album->BandID,
            'Title' => $title,
            'FileName' => $filename,
            'Ord' => $ord,
            'Duration' => $duration
        ]);

        cache()->flush();
    }
}
