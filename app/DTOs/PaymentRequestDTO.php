<?php

namespace App\DTOs;

class PaymentRequestDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $description,
        public readonly string $cartId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            amount: $data['amount'],
            description: $data['description'] ?? 'Order Payment',
            cartId: $data['cart_id']
        );
    }
}
