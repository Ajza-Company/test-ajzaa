<?php

namespace App\Services\Frontend;
use App\Repositories\Services\Messaging\Sms\SmsInterface;
use App\Repositories\SMS\OTPProviderInterface;
use App\Repositories\SMS\SMSProviderInterface;
use InvalidArgumentException;

class SmsService
{
    private SMSProviderInterface $currentProvider;

    public function __construct(
        private array $providers,
        private string $defaultProvider
    ) {
        $this->setProvider($this->defaultProvider);
    }

    public function setProvider(string $provider): void
    {
        if (!isset($this->providers[$provider])) {
            throw new InvalidArgumentException("SMS provider '{$provider}' not found");
        }

        $this->currentProvider = $this->providers[$provider];
    }

    public function sendMessage(string $phone, string $message): bool
    {
        return $this->currentProvider->sendMessage($phone, $message);
    }

    public function generateAndSendOTP(string $phone): array
    {
        if (!$this->currentProvider instanceof OTPProviderInterface) {
            throw new InvalidArgumentException("Current provider does not support OTP operations");
        }

        return $this->currentProvider->generateAndSendOTP($phone);
    }

    public function verifyOTP(string $phone, string $code): bool
    {
        if (!$this->currentProvider instanceof OTPProviderInterface) {
            throw new InvalidArgumentException("Current provider does not support OTP operations");
        }

        return $this->currentProvider->verifyOTP($phone, $code);
    }
}
