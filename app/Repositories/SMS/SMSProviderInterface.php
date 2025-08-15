<?php

namespace App\Repositories\SMS;

interface SMSProviderInterface
{
    public function sendMessage(string $to, string $message): bool;
}
