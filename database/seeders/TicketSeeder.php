<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TicketWallet;
use App\Models\TicketTransaction;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach($users as $user){
            DB::transaction(function () use ($user) {
                $wallet = TicketWallet::firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0]
                );
                $wallet->increment('balance', 1);
                TicketTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'grant',
                    'amount' => 1,
                    'description' => 'Bonus tiket dari admin'
                ]);
            });
        }
    }
}
