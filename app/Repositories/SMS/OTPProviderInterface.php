<?php

namespace App\Repositories\SMS;

interface OTPProviderInterface
{
    public function generateAndSendOTP(string $phone): array;
    public function verifyOTP(string $phone, string $code): bool;
}
