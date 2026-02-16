<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class personality extends Model
{
    protected $fillable = [
        'name',
        'personality_type',
        'specializatiion',
        'description',
    ];
    protected $casts = [
        'personality_type' => 'array',
        'specializatiion' => 'array',
    ];
    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class);
    }
}
