<?php

namespace App\Repositories\SMS\Providers;

use App\Repositories\SMS\OTPProviderInterface;
use App\Repositories\SMS\SMSProviderInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Provider1 implements SMSProviderInterface, OTPProviderInterface
{
    /**
     * @throws ConnectionException
     */
    public function sendMessage(string $to, string $message): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authorization' => config('services.sms.provider1.secret'),
            'Content-Type' => 'application/json',
        ])->post(config('services.sms.provider1.url') . '/sendOTP', [
            'phone' => $to,
            'method' => 'sms',
            'template_id' => 1,
            'otp_format' => 'numeric',
            'number_of_digits' => 4,
            'is_fallback_on' => false
        ]);

        if (!$response->ok()) {
            return false;
        }
        return $response['success'];
    }

    /**
     * @throws ConnectionException
     */
    public function generateAndSendOTP(string $phone): array
    {
        // Provider1 handles OTP generation internally
        $response = $this->sendMessage($phone, '');

        return [
            'success' => $response,
            'expiresAt' => now()->addMinutes(10)
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function verifyOTP(string $phone, string $code): bool
    {
        // Implement Provider1's OTP verification API call
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Authorization' => config('services.sms.provider1.secret'),
        ])->post(config('services.sms.provider1.url') . '/verifyOTP', [
            'phone' => $phone,
            'otp' => $code
        ]);

        \Log::info($response->json());

        return $response->ok();
    }
}
