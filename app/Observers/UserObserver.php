<?php

namespace App\Observers;

use App\Models\TicketTransaction;
use App\Models\TicketWallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        DB::transaction(function () use ($user)
            {
                TicketWallet::create([
                    'user_id' => $user->id,
                    'balance' => 3,
               ]);

               TicketTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'grant',
                    'amount' => 3,
                    'description' => 'Bonus pengguna baru',
               ]);
        });
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
