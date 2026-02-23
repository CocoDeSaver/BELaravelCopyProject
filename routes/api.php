<?php

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
    Route::get('/tickets/balance', [TicketController::class, 'balance']);
});

require __DIR__.'/auth.php';