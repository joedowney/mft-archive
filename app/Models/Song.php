<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Song extends Model
{
    use Searchable;

    protected $table = 'mft_songs';

    protected $primaryKey = 'ID';

    protected $guarded = [];

    public $timestamps = false;

    public function album()
    {
        return $this->belongsTo(Album::class, 'AlbumID', 'ID')
            ->where('mft_albums.Enabled', 1);
    }

    public function toSearchableArray()
    {
        return [
            'ID' => $this->ID,
            'Title' => $this->Title,
        ];
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->where('Enabled', 1);
    }
}
