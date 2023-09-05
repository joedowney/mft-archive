<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function show()
    {
        $this->validate(request(), ['path' => 'required']);

        $url = Storage::temporaryUrl(request('path'), now()->addMinutes(5));

        return redirect()->to($url);
    }
}
