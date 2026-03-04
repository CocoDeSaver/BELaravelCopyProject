<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function balance(Request $request)
    {
        $wallet = $request->user()->chatWallet;
    
        return response()->json([
            'balance' => $wallet?->balance ?? 0
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'persona' => 'required|string'
        ]);

        $user = $request->user();
        $wallet = $user->chatWallet;

        if (!$wallet || $wallet->balance <= 0){
            return response()->json([
                'message' => 'Chat balance habis'
            ], 403);
        }

        $response = Http::post(env('AI_SERVICE_URL') . '/chat', [
            'message' => $request->message,
            'persona' => $request->persona,
            // 'user_id' => $user->id,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'AI service unavailable'
            ], 500);
        }

        $data = $response->json();

        if ($data['status'] !== 'success'){
            return response()->json([
                'message' => $data['message'] ?? 'AI error'
            ], 500);
        }

        $aiReply = $data['response'];

        DB::transaction(function () use ($wallet, $user){
            $wallet->decrement('balance');
            $user->chatTransactions()->create([
                'type' => 'debit',
                'amount' => 1,
                'description' => 'Chat usage'
            ]);
        });

        return response()->json([
            'reply' => $aiReply,
            'remaining_balance' => $wallet->fresh()->balance
        ]);
    }
}
