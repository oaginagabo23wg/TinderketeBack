<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'img',
        'iframe',
        'url'
    ];

    // Relations
    public function tournaments()
    {
        return $this->belongsTo(Tournament::class, 'id');
    }

}
