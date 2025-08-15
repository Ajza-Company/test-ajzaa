<?php

namespace App\Broadcasting\Sms;


use App\Repositories\SMS\SMSProviderInterface;

class SMSService
{
    public function __construct(private SMSProviderInterface $provider)
    {
    }

    public function setProvider(SMSProviderInterface $provider): void
    {
        $this->provider = $provider;
    }

    public function sendMessage(string $to, string $message): bool
    {
        return $this->provider->send($to, $message);
    }
}
