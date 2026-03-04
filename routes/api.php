<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Personality;
use App\Http\Controllers\Api\TicketController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/personalities', function () {
    return Personality::all();
});

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/chat/balance', [ChatController::class, 'balance']);
});

Route::middleware(['auth:sanctum'])->post('/chat/send', [ChatController::class, 'send']);

require __DIR__.'/auth.php';