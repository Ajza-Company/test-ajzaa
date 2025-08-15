<?php

namespace App\Repositories\Services\Messaging\Sms\Send;

use App\Repositories\Services\Messaging\Sms\SmsInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SmsProvider1 implements SmsInterface
{
    public function __construct(protected array $config)
    {

    }

    /**
     * @inheritDoc
     * @throws ConnectionException
     */
    public function send(string $to, ?string $message = null, ?array $options = []): bool
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authorization' => config('services.sms.provider1.secret'),
            'Content-Type' => 'application/json',
        ])->post(config('services.sms.provider1.url'), [
            'phone' => $to,
            'method' => 'sms',
            'template_id' => 1,
            'otp_format' => 'numeric',
            'number_of_digits' => 4,
            'is_fallback_on' => false
        ]);

        return $response->ok();
    }
}
