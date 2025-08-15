<?php

namespace App\Services\Payment;

use App\DTOs\PaymentRequestDTO;
use App\DTOs\PaymentResponseDTO;
use App\Models\Order;

class PaymentService
{

    public function __construct(private PaymentGatewayInterface $gateway)
    {
    }

    public function createPayment(PaymentRequestDTO $data, Order $order): PaymentResponseDTO
    {
        return $this->gateway->createPayment($data, $order);
    }

    public function verifyPayment(array $data): PaymentResponseDTO
    {
        return $this->gateway->verifyPayment($data);
    }
}
