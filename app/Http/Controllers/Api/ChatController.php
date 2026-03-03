<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function balance(Request $request)
    {
        $wallet = $request->user()->chatWallet;
    
        return response()->json([
            'balance' => $wallet?->balance ?? 0
        ]);
    }
}
