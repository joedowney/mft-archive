<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Album extends Model
{
    use Searchable;

    protected $table = 'mft_albums';

    protected $primaryKey = 'ID';

    protected $appends = ['ImagePath'];

    public $timestamps = false;

    public function songs()
    {
        return $this->hasMany(Song::class, 'AlbumID')->orderBy('Ord');
    }

    public function band()
    {
        return $this->belongsTo(Band::class, 'BandID', 'ID')
            ->where('mft_bands.Enabled', 1);
    }

    public function getImagePathAttribute()
    {
        if ($this->Image)
            return ('/images?path=_images/album/' . $this->Image);
        return '/img/default_band.jpg';
    }

    public function toSearchableArray()
    {
        return [
            'ID' => $this->ID,
            'Title' => $this->Title,
            'Description' => $this->Description
        ];
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->where('Enabled', 1);
    }
}
