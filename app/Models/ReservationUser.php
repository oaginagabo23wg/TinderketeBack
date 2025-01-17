<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationUser extends Model
{
    use HasFactory;

    protected $table = 'reservation_user';
    protected $fillable = ['user_id', 'reservation_id'];
}
