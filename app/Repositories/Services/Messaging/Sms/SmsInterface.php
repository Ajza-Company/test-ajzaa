<?php

namespace App\Repositories\Services\Messaging\Sms;

interface SmsInterface
{
    /**
     * Create new resource
     *
     * @param string $to
     * @param string|null $message
     * @param array|null $options
     * @return mixed
     */
    public function send(string $to, ?string $message = null, ?array $options = []): bool;
}
