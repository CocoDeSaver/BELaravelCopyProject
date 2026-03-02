<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSession extends Model
{
    protected $fillable = [
        'user_id',
        'remaining_messages',
        'total_messages',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
