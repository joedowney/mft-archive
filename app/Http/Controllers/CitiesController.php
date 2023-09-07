<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CitiesController extends Controller
{
    public function index()
    {
        $places = $this->getCitiesAndStates();

        return Inertia::render('Cities/CitiesIndex')
            ->with('cities', $places['cities'])
            ->with('states', $places['states']);
    }

    public function show($slug)
    {
        $city = Cache::rememberForever('city_' . $slug, function() use ($slug) {
            return City::where('Slug', $slug)->with('bands', function($query) { return $query->withCount('albums')->withCount('songs'); })->first();
        });

        if ( ! $city) abort(404);

        $state = Cache::rememberForever('state_' . $city->StateID, function() use ($city) {
            return \DB::table('mft_states')->where('ID', $city->StateID)->first();
        });

        return Inertia::render('Cities/CitiesShow')
            ->with('city', $city)
            ->with('state', $state);
    }

    private function getCitiesAndStates()
    {
        $cities = Cache::rememberForever('cities', function() {
            return City::withCount('bands')->orderBy('City')->get();
        });

        $states = Cache::rememberForever('states', function() use ($cities) {
            $state_ids = $cities->map(function($city) { return $city->StateID; })->unique();
            return DB::table('mft_states')->whereIn('ID', $state_ids)->get();
        });

        return ['cities' => $cities, 'states' => $states];
    }
}
