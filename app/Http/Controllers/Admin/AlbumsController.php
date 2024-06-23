<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Band;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlbumsController extends Controller
{
    public function edit($album_id)
    {
        $album = Album::with('band')->with('songs')->findOrFail($album_id);

        return Inertia::render('Admin/Albums/Edit')->with('album', $album);
    }

    public function update($album_id)
    {
        $album = Album::findOrFail($album_id);

        $this->validate(request(), ['title' => 'required']);

        $album->update([
            'Title' => request('title'),
            'Description' => request('description') ?? ''
        ]);
    }

    public function updateImage($album_id)
    {
        $album = Album::findOrFail($album_id);

        $file = request()->file('image');

        $filename = $album_id . '_' . $file->getClientOriginalName();

        $file->storeAs('_images/album/', $filename);

        $album->update(['Image' => $filename]);
    }

    public function deleteImage($album_id)
    {
        $album = Album::findOrFail($album_id);

        $album->update(['Image' => '']);
    }

    public function store()
    {
        $this->validate(request(), [
            'band_id' => 'required',
            'title' => 'required'
        ]);

        $band = Band::findOrFail(request('band_id'));

        $id = Album::orderBy('ID', 'DESC')->first()->ID + 1;

        $band->albums()->create([
            'ID' => $id,
            'BandID' => request('band_id'),
            'Title' => request('title'),
            'Description' => '',
        ]);

        return $id;
    }
}
