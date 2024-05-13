<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Inertia\Inertia;

class AlbumsController extends Controller
{
    public function data($album_id)
    {
        return Album::with('band')->with('songs')->find($album_id);
    }

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
            'Description' => request('description')
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
}
