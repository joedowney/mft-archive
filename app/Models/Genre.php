<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'mft_genres';

    public $timestamps = false;

    protected $guarded = null;

    public function bands()
    {
        return $this->belongsToMany(
            Band::class,
            'band_genre',
            'GenreID',
            'BandID',
            'ID',
            'ID'
        )->orderBy('mft_bands.Name');
    }
}
