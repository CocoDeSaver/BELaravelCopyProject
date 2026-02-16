<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function personality()
    {
        return $this->belongsTo(Personality::class);
    }
    public function messages()
    {
        return $this->hasMany(ChatMessagge::class, 'session_id');
    }
}
