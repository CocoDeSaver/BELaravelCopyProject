<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }
}
