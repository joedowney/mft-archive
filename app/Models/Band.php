<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Band extends Model
{
    use Searchable;

    protected $table = 'mft_bands';

    protected $primaryKey = 'ID';

    protected $appends = ['ImagePath'];

    public $timestamps = false;

    protected $guarded = [];

    public function getImagePathAttribute()
    {
        return $this->Image
            ? url('images?path=_images/band/').$this->Image
            : '/img/default_band.jpg';
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'BandID')
            ->where('mft_albums.Enabled', 1)
            ->orderBy('mft_albums.Ord');
    }

    public function songs()
    {
        return $this->hasManyThrough(Song::class, Album::class, 'BandID', 'AlbumID', 'ID')
            ->where('mft_albums.Enabled', 1)
            ->where('mft_songs.Enabled', 1);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'CityID', 'ID');
    }

    public function toSearchableArray()
    {
        return [
            'ID' => $this->ID,
            'Name' => $this->Name,
            'Enabled' => $this->Enabled,
            'Path' => $this->Path,
            'Description' => $this->Description,
            'Members' => $this->Members,
            'City' => optional($this->city)->City,
        ];
    }

    public function relatedBands()
    {
        return $this->belongsToMany(Band::class, 'related_bands', 'band_id', 'related_band_id');
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->where('Enabled', 1)->with('city');
    }
}
