<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'mft_cities';

    public function bands()
    {
        return $this->hasMany(Band::class, 'CityID', 'ID')->where('Enabled', 1);
    }
}
