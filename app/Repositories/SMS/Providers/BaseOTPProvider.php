<?php

namespace App\Repositories\SMS\Providers;

use App\Models\OtpCode;
use Carbon\Carbon;

class BaseOTPProvider
{
    protected function generateOTPCode(): string
    {
        return (string) rand(1000, 9999);
    }

    protected function createOTPRecord(string $phone, string $code): void
    {
        OtpCode::create([
            'full_mobile' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'is_verified' => false
        ]);
    }

    protected function validateOTPRecord(string $phone, string $code): bool
    {
        $otpRecord = OtpCode::where([
            'full_mobile' => $phone,
            'code' => $code,
            'is_verified' => false
        ])->first();

        if (!$otpRecord || Carbon::now()->isAfter($otpRecord->expires_at)) {
            return false;
        }

        $otpRecord->update([
            'is_verified' => true,
            'expires_at' => Carbon::now()
        ]);

        return true;
    }
}
