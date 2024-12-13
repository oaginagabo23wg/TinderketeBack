<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'coordinates',
        'img',
    ];

    // Relations
    public function tournaments()
    {
        return $this->belongsTo(Tournament::class, 'id', 'location_id');
    }

}
