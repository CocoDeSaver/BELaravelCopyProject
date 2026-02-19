<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach($users as $user){
            Ticket::create([
                'user_id' => $user->id,
                'amount' => 3,
                'type' => 'bonus',
                'description' => 'Bonus tiket untuk pengguna baru',
                'expires_at' => null,
            ]);
        }
    }
}
