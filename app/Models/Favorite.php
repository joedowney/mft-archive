<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $guarded = [];

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class, 'ID', 'song_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
