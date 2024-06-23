<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use App\Models\Band;
use App\Models\City;
use App\Models\Genre;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Support\BandsAlphaList;
use App\Http\Controllers\Controller;

class BandsController extends Controller
{
    use BandsAlphaList;

    public function alpha($letter) : Response
    {
        $bands = $this->getBandsByLetter($letter);

        return Inertia::render('Admin/Bands/Alpha')
            ->with('letter', $letter)
            ->with('bands', $bands);
    }

    public function edit($band_id) //: Response
    {
        $band = Band::where('ID', $band_id)
            ->with('albums', function ($query) {
                return $query->with('songs')->withCount('songs');
            })
            ->with('relatedBands')
            ->firstOrFail();

        $albums = $band->albums()->select(['ID', 'Title'])->get();

        $genres = Genre::select(['ID', 'Name'])->get();
        $bands = Band::select(['ID', 'Name'])->get();
        $cities = City::select(['ID', 'City'])->get();

        return Inertia::render('Admin/Bands/Edit')
            ->with('band', $band)
            ->with('albums', $albums)
            ->with('genres', $genres)
            ->with('bands', $bands)
            ->with('cities', $cities);
    }

    public function updateName($band_id)
    {
        $band = Band::findOrFail($band_id);

        $this->validate(request(), [
            'name' => 'required',
            'url' => ['required', Rule::unique('mft_bands','URL')->ignore($band_id)],
            'alpha' => 'required'
        ]);

        $band->update([
            'Name' => request('name'),
            'URL' => request('url'),
            'Alpha' => request('alpha')
        ]);
    }

    public function updateInfo($band_id)
    {
        $band = Band::findOrFail($band_id);

        $band->update([
            'Members' => request('members'),
            'Description' => request('description'),
            'CityID' => request('city')
        ]);
    }

    public function updateImage($band_id)
    {
        $band = Band::findOrFail($band_id);

        $file = request()->file('image');

        $filename = $band_id . '_' . $file->getClientOriginalName();

        $file->storeAs('_images/band/', $filename);

        $band->update(['Image' => $filename]);
    }

    public function deleteImage($band_id)
    {
        $band = Band::findOrFail($band_id);

        $band->update(['Image' => '']);
    }

    public function updateGenres($band_id)
    {
        $band = Band::findOrFail($band_id);

        $genre_ids = collect(request('genres'))->implode(',');

        $band->update(['GenreID' => $genre_ids]);
    }

    public function updateRelatedBands($band_id)
    {
        $band = Band::findOrFail($band_id);

        $band_ids = collect(request('bands'))->implode(',');

        $band->update(['Related' => $band_ids]);
    }

    public function create()
    {
        $genres = Genre::select(['ID', 'Name'])->get();
        $bands = Band::select(['ID', 'Name'])->get();
        $cities = City::select(['ID', 'City'])->get();

        return Inertia::render('Admin/Bands/Create')
            ->with('genres', $genres)
            ->with('bands', $bands)
            ->with('cities', $cities);
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required',
            'members' => '',
            'city' => 'integer',
            'description' => ''
        ]);

        $alpha = Str::lower(request('name'));
        $slug = Str::slug(request('name'));
        $id = Band::orderBy('ID', 'DESC')->first()->ID + 1;

        $band = Band::create([
            'ID' => $id,
            'Name' => request('name'),
            'URL' => $slug,
            'Alpha' => $alpha,
            'Sort' => Str::substr($alpha, 0, 1),
            'Path' => $slug,
            'Description' => request('description'),
            'Members' => request('members'),
            'CityID' => request('city'),
            'Store_Btn' => '',
            'Enabled' => 1
        ]);

        return $id;
    }
}
