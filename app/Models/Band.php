<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Band extends Model
{
    protected $table = 'mft_bands';

    protected $primaryKey = 'ID';

    protected $appends = ['ImagePath'];

    public $timestamps = false;

    protected $fillable = ['Description'];

    public function getImagePathAttribute()
    {
        return $this->Image
            ? url('images?path=_images/band/') . $this->Image
            : '/img/default_band.jpg';
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'BandID')
            ->where('mft_albums.Enabled', 1)
            ->orderBy('Ord');
    }

    public function songs()
    {
        return $this->hasManyThrough(Song::class, Album::class, 'ID', 'AlbumID', 'ID')
            ->where('mft_albums.Enabled', 1)
            ->where('mft_songs.Enabled', 1);
    }
}
