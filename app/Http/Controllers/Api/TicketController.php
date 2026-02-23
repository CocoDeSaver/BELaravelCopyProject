<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function balance(Request $request, TicketService $ticketService)
    {
        $balance = $ticketService->getBalance($request->user());
        return response()->json([
            'ticket_balance' => $balance
        ]);
    }
}
