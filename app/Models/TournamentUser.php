<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentUser extends Model
{
    protected $table = 'tournament_user';
    protected $fillable = ['user_id', 'tournament_id'];
}
