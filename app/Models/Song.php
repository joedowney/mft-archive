<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Song extends Model
{
    use Searchable;

    protected $table = 'mft_songs';

    protected $primaryKey = 'ID';

    public function album()
    {
        return $this->belongsTo(Album::class, 'AlbumID', 'ID');
    }

    public function toSearchableArray()
    {
        return [
            'ID' => $this->ID,
            'Title' => $this->Title
        ];
    }

}
