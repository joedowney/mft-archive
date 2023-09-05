<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'mft_albums';

    protected $primaryKey = 'ID';

    public function songs()
    {
        return $this->hasMany(Song::class, 'AlbumID')->orderBy('Ord');
    }
}
