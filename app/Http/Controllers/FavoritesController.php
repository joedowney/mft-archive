<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;

class FavoritesController extends Controller
{
    public function update()
    {
        $this->validate(request(), [
            'song_id' => 'required',
            'state' => Rule::in(['on', 'off'])
        ]);

        if (request('state') === 'on') {
            auth()->user()->favorites()->where('song_id', request('song_id'))->firstOrCreate();
        }

        if (request('state') === 'off') {
            auth()->user()->favorites()->where('song_id', request('song_id'))->delete();
        }
    }
}
