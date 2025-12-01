<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['cinema_id', 'name', 'total_seats'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}