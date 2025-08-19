<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Route::get('payment/status', function () {
    return view('welcome');
})->name('payment.status');

// Broadcasting routes (مطلوبة للـ Pusher authentication)
Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['auth:sanctum']);
