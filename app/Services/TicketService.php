<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;

class TicketService
{
    public function getBalance (User $user): int
    {
        return Ticket::where('user_id', $user->id)->where(function ($query)
        {
            $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->sum('amount');
    }

    public function consume(User $user, int $amount = 1): void
    {
        Ticket::create([
            'user_id' => $user->id,
            'amount' => -$amount,
            'type' => 'usage',
            'description' => 'Consumed tickets for usage',
        ]);
    }

    public function grant(User $user, int $amount, ?string $description = null): void
    {
        Ticket::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'grant',
            'description' => $description,
        ]);
    }
}