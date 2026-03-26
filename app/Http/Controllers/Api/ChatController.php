<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string'
        ]);

        $user = $request->user();
        $wallet = $user->chatWallet;

        if (!$wallet || $wallet->balance <= 0){
            return response()->json([
                'message' => 'Chat balance habis'
            ], 403);
        }
        $session = ChatSession::with('personality')
        ->where('id', $request->session_id)->where('user_id', Auth::id())->firstOrFail();

        $history = $session->messages()
        ->orderBy('created_at', 'asc')->take(8)->get(['role','message']);

        $messages = $history->map(function ($msg) {
            return [
                "role" => $msg->role,
                "content" => $msg->message
            ];
        })->values();

        ChatMessage::create([
            'session_id' => $session->id,
            'role' => 'user',
            'message'=> $request->message,
        ]);

        if (is_null($session->title)){
            $words = explode(' ', trim($request->message));
            $title =implode(' ', array_slice($words, 0, 3));
            $title = Str::limit($title, 30);

            $session->update([
                'title' => $title
            ]);
        }

        $response = Http::post(env('AI_SERVICE_URL') . '/chat', [
            'message' => $request->message,
            'persona' => $session->personality->slug,
            'history' => $messages
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'AI service unavailable'
            ], 500);
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'success'){
            return response()->json([
                'message' => $data['message'] ?? 'AI error'
            ], 500);
        }

        $aiReply = $data['reply'];
        $alert = $data['alert'] ?? 'safe';

        ChatMessage::create([
            'session_id' => $session->id,
            'role' => 'assistant',
            'message'=> $aiReply
        ]);

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
            'alert' => $alert,
            'remaining_balance' => $wallet->fresh()->balance
        ]);
    }
}
