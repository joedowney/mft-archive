<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Inertia\Inertia;
use Inertia\Response;
use App\Support\BandsAlphaList;
use Illuminate\Support\Facades\Cache;

class BandsController extends Controller
{
    use BandsAlphaList;

    public function index() : Response
    {
        $alphabet = $this->getBandsAlphabet();

        return Inertia::render('Bands/BandsIndex')->with('alphabet', $alphabet);
    }

    public function alpha($letter) : Response
    {
        $bands = $this->getBandsByLetter($letter);

        return Inertia::render('Bands/Alpha')
            ->with('letter', $letter)
            ->with('bands', $bands);
    }

    public function show($slug) : Response
    {
        $band = Cache::rememberForever('band_'.$slug, function () use ($slug) {
            return Band::where('URL', $slug)
                ->with('albums', function ($query) {
                    return $query->with('songs')->withCount('songs');
                })
                ->with('relatedBands')
                ->firstOrFail();
        });

        return Inertia::render('Bands/BandsShow')->with('band', $band);
    }

    public function data($band_id)
    {
        return Cache::rememberForever('band_data_'.$band_id, function () use ($band_id) {
            return Band::where('ID', $band_id)->with('albums.songs')->firstOrFail();
        });
    }
}
