<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TornamentUser extends Model
{
    protected $fillable = [
        'tournament_id',
        'user_id',
    ];
}
