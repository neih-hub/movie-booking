<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'data',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'created_at' => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
