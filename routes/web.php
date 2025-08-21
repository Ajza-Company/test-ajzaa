<?php

use App\Http\Controllers\api\v1\Frontend\InterPayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Route::get('payment/status', function () {
    return view('welcome');
})->name('payment.status');

// Broadcasting routes (مطلوبة للـ Pusher authentication)
Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['auth:sanctum']);

Route::post('interpay/callback', [InterPayController::class, 'callback']);