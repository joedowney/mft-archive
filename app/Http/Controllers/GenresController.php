<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class GenresController extends Controller
{
    public function index()
    {
        $genres = Cache::rememberForever('genres', function () {
            return Genre::orderBy('Name')->withCount('bands')->get();
        });

        return Inertia::render('Genres/GenresIndex')
            ->with('genres', $genres);
    }

    public function show($slug)
    {
        $genre = Cache::rememberForever('genre_'.$slug, function () use ($slug) {
            return Genre::where('Slug', $slug)
                ->with('bands', function ($query) {
                    return $query->withCount('albums')->withCount('songs');
                })->first();
        });

        if (! $genre) {
            abort(404);
        }

        return Inertia::render('Genres/GenresShow')->with('genre', $genre);
    }
}
