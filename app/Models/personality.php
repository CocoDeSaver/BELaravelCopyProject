<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class personality extends Model
{
    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }
}
