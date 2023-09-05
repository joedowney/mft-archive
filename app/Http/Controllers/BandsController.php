<?php

namespace App\Http\Controllers;

use App\Models\Band;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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
        $band = Cache::get('band_' . $slug, function() use ($slug) {
            return Band::where('URL', $slug)
                ->with('albums.songs')
                ->firstOrFail();
        });

        return Inertia::render('Bands/BandsShow')->with('band', $band);
    }

    private function getBandsAlphabet()
    {
        return Cache::get('alphabet', function() {
            return Band::groupBy('Sort')
                ->select('Sort', DB::raw('count(*) as total'))
                ->orderBy('Sort')
                ->get();
        });
    }

    private function getBandsByLetter($letter)
    {
        return Cache::get('alpha_bands_' . $letter, function() use ($letter) {
            return Band::where('Sort', $letter)->where('Enabled', 1)->orderBy('Alpha')->get();
        });
    }
}
