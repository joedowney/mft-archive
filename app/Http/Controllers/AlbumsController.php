<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Inertia\Inertia;

class AlbumsController extends Controller
{
    public function data($album_id)
    {
        return Album::with(
            'band')->with('songs')->find($album_id);
    }
}
