<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Song;
use App\Support\MP3File;
use App\Support\SongDuration;
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
            'file' => 'required|file'
        ]);

        $album = Album::findOrFail(request('album_id'));

        $file = request()->file('file');
        $filename = $file->getClientOriginalName();
        $duration = (new SongDuration())->getDuration($file);
        $file_path = $album->band->Sort.'/'
            . $album->band->Path.'/'
            . $filename;
        $file->storeAs($file_path);

        $title = request('title') ?: pathinfo($filename, PATHINFO_FILENAME);
        $ord = (optional(Song::where('AlbumID', request('album_id'))->orderBy('Ord', 'desc')->first())->Ord ?? 0) + 1;
        $id = Song::orderBy('ID', 'DESC')->limit(1)->first('ID')->ID + 1;

        $song = Song::create([
            'ID' => $id,
            'AlbumID' => $album->ID,
            'BandID' => $album->BandID,
            'Title' => $title,
            'FileName' => $filename,
            'Ord' => $ord,
            'Duration' => $duration
        ]);

        cache()->flush();

        return $song->refresh();
    }

    public function update($song_id)
    {
        $this->validate(request(), ['title' => 'required']);

        $song = Song::findOrFail($song_id);

        $song->update(['title' => request('title')]);
    }

    public function updateOrder()
    {
        $this->validate(request(), [
            'ids' => 'required',
            'album_id' => 'required'
        ]);

        $album = Album::find(request('album_id'));

        $i = 0;
        foreach(request('ids') as $id) {
            $album->songs()->where('ID', $id)->update(['Ord' => $i]);
            $i++;
        }
    }

    public function delete($song_id)
    {
        $song = Song::findOrFail($song_id);
        $song->delete();
    }
}
