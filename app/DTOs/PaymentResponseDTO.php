<?php

namespace App\DTOs;

class PaymentResponseDTO
{
    public function __construct(
        public readonly string $status,
        public readonly string $message,
        public readonly ?string $redirectUrl = null,
        public readonly ?string $transactionRef = null,
        public readonly array $rawResponse = []
    ) {}
}
