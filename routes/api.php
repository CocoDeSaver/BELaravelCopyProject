<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Personality;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/personalities', function () {
    return Personality::all();
});

require __DIR__.'/auth.php';