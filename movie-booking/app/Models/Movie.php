<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'genre',
        'poster',
        'release_date',
    ];

    protected $casts = [
        'genre' => 'array'
    ];
    public function showtimes()
{
    return $this->hasMany(Showtime::class, 'movie_id');
}
}