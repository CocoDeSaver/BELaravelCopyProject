<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Personality;
use App\Http\Controllers\ChatSessionController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/personalities', function () {
    return Personality::all();
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/chat/balance', [ChatController::class, 'balance']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::post('/chat-sessions', [ChatSessionController::class, 'store']);
    Route::get('/chat-sessions', [ChatSessionController::class, 'index']);
    Route::get('/chat-sessions/{id}/messages', [ChatSessionController::class, 'messages']);
});

require __DIR__.'/auth.php';