<?php

namespace App\Repositories\SMS\Providers;

use App\Repositories\SMS\OTPProviderInterface;
use App\Repositories\SMS\SMSProviderInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Provider2 extends BaseOTPProvider implements SMSProviderInterface
{
    public function sendMessage(string $to, string $message): bool
    {
        // Implement Provider2's SMS sending logic
        return true;
    }

    public function generateAndSendOTP(string $phone): array
    {
        $code = $this->generateOTPCode();
        $message = "Your verification code is {$code}";

        $success = $this->sendMessage($phone, $message);

        if ($success) {
            $this->createOTPRecord($phone, $code);
        }

        return [
            'success' => $success,
            'code' => $code, // Only for development/testing
            'expiresAt' => now()->addMinutes(10)
        ];
    }

    public function verifyOTP(string $phone, string $code): bool
    {
        return $this->validateOTPRecord($phone, $code);
    }
}
