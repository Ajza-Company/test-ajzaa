<?php

use App\Enums\EncodingMethodsEnum;
use App\Models\Order;
use App\Models\RepOrder;
use Illuminate\Contracts\Encryption\DecryptException;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('generateOrderId')) {
    /**
     * Returns decoded Item
     */
    function generateOrderId(string $categoryPrefix, string $type = 'normal'): ?string
    {
        // Get current timestamp
        $timestamp = now()->format('YmdHis');

        // Generate a random number between 1000-9999
        $random = rand(100, 999);

        // Combine prefix, timestamp and random number
        $orderId = strtoupper($categoryPrefix) . $timestamp . $random;

        // Check if order ID already exists (just to be extra safe)
        if ($type === 'normal') {
            while (Order::where('order_id', $orderId)->exists()) {
                $random = rand(100, 999);
                $orderId = strtoupper($categoryPrefix) . $timestamp . $random;
            }
        }elseif ($type === 'rep') {
            while (RepOrder::where('order_number', $orderId)->exists()) {
                $random = rand(100, 999);
                $orderId = strtoupper($categoryPrefix) . $timestamp . $random;
            }
        }


        return $orderId;
    }
}
