<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
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
