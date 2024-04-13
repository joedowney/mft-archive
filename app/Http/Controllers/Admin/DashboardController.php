<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Band;
use App\Support\BandsAlphaList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    use BandsAlphaList;

    public function show() : Response
    {
        $alphabet = $this->getBandsAlphabet();

        return Inertia::render('Admin/Dashboard')->with('alphabet', $alphabet);
    }
}
