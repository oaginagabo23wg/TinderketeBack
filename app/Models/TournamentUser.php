<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TournamentUser extends Model
{
    use HasFactory;

    protected $table = 'tournament_users';
    protected $fillable = ['user_id', 'tournament_id'];
}
