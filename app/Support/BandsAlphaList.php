<?php

namespace App\Support;

use App\Models\Band;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

trait BandsAlphaList
{
    private function getBandsAlphabet()
    {
        return Cache::rememberForever('alphabet', function () {
            return Band::groupBy('Sort')
                ->select('Sort', DB::raw('count(*) as total'))
                ->orderBy('Sort')
                ->get();
        });
    }

    private function getBandsByLetter($letter)
    {
        return Cache::rememberForever('alpha_bands_'.$letter, function () use ($letter) {
            return Band::where('Sort', $letter)
                ->where('Enabled', 1)
                ->orderBy('Alpha')
                ->withCount('albums')
                ->withCount('songs')
                ->get();
        });
    }
}
