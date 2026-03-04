<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatSessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'personality_id' => 'required|exists:personalities,id'
        ]);
        $session = ChatSession::create([
            'user_id' => Auth::id(),
            'personality_id' => $request->personality_id,
            'title' => null
        ]);
        return response()->json([
            'session_id' => $session->id,
            'message' => 'Chat session created successfully'
        ]);
    }
    public function index()
    {
        $sessions = ChatSession::where('user_id', Auth::id())
        ->latest()->get(['id', 'title', 'created_at']);

        return response()->json($sessions);
    }
    public function messages($id)
    {
        $session = ChatSession::where('id', $id)
        ->where('user_id', Auth::id())->firstOrFail();

        $messages = $session->messages()
        ->OrderBy('created_at', 'asc')->get(['id', 'role', 'message', 'created_at']);

        return response()->json($messages);
    }
}
