<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BandsController extends Controller
{
    public function index()
    {
        $alphabet = $this->getBandsAlphabet();

        return Inertia::render('Bands/BandsIndex')->with('alphabet', $alphabet);
    }

    public function alpha($letter)
    {
        $bands = $this->getBandsByLetter($letter);

        return Inertia::render('Bands/Alpha')
            ->with('letter', $letter)
            ->with('bands', $bands);
    }

    public function show($slug)
    {
        $band = Cache::rememberForever('band_' . $slug, function() use ($slug) {
            return Band::where('URL', $slug)
                ->with('albums', function($query) {
                    return $query->with('songs')->withCount('songs');
                })
                ->with('relatedBands')
                ->firstOrFail();
        });

        return Inertia::render('Bands/BandsShow')->with('band', $band);
    }

    public function data($band_id)
    {
        return Cache::rememberForever('band_data_' . $band_id, function() use ($band_id) {
            return Band::where('ID', $band_id)->with('albums.songs')->firstOrFail();
        });
    }

    private function getBandsAlphabet()
    {
        return Cache::rememberForever('alphabet', function() {
            return Band::groupBy('Sort')
                ->select('Sort', DB::raw('count(*) as total'))
                ->orderBy('Sort')
                ->get();
        });
    }

    private function getBandsByLetter($letter)
    {
        return Cache::rememberForever('alpha_bands_' . $letter, function() use ($letter) {
            return Band::where('Sort', $letter)
                ->where('Enabled', 1)
                ->orderBy('Alpha')
                ->withCount('albums')
                ->withCount('songs')
                ->get();
        });
    }
}
