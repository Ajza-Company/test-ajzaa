<?php

namespace App\Services\Payment;

use App\DTOs\PaymentRequestDTO;
use App\DTOs\PaymentResponseDTO;
use App\Models\Order;

interface PaymentGatewayInterface
{
    public function createPayment(PaymentRequestDTO $data, Order $order): PaymentResponseDTO;
    public function verifyPayment(array $data): PaymentResponseDTO;
}
