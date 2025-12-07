<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingFood extends Model
{
    protected $table = 'booking_foods';

    protected $fillable = ['booking_id', 'food_id', 'quantity', 'price'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
