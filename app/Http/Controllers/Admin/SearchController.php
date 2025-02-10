<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Band;
use App\Models\Song;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search()
    {
        $q = request('q');

        return Band::search($q)->take(10)->get();
    }
}
