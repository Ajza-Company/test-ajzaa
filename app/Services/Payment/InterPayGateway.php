<?php

namespace App\Services\Payment;

use App\DTOs\PaymentRequestDTO;
use App\DTOs\PaymentResponseDTO;
use App\Exceptions\PaymentGatewayException;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickPayGateway implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $profileId;
    private string $serverKey;

    public function __construct()
    {
        $this->baseUrl = config('services.payment.clickpay.base_url');
        $this->profileId = config('services.payment.clickpay.profile_id');
        $this->serverKey = config('services.payment.clickpay.server_key');
    }

    /**
     * @throws PaymentGatewayException
     */
    public function createPayment(PaymentRequestDTO $request, Order $order): PaymentResponseDTO
    {
        try {
            $response = Http::withHeaders([
                'authorization' => $this->serverKey,
                'content-type' => 'application/json',
            ])->post($this->baseUrl . 'payment/request', $this->buildPaymentPayload($request, $order));

            if (!$response->ok()) {
                throw new PaymentGatewayException(
                    "ClickPay API error: " . ($response['message'] ?? 'Unknown error'),
                    $response->status()
                );
            }

            $data = $response->json();
            Log::info('ClickPay payment created', $data);

            // If redirect URL exists, payment needs additional steps
            if (isset($data['redirect_url'])) {
                return new PaymentResponseDTO(
                    status: 'pending',
                    message: 'Redirect required to complete payment',
                    redirectUrl: $data['redirect_url'],
                    transactionRef: $data['tran_ref'] ?? null,
                    rawResponse: $data
                );
            }

            throw new PaymentGatewayException(
                "ClickPay API error: Unable to create payment"
            );

        } catch (\Exception $e) {
            Log::error('ClickPay payment creation failed', [
                'error' => $e->getMessage(),
                'request' => $request
            ]);
            throw new PaymentGatewayException(
                'Payment creation failed: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function verifyPayment(array $transactionRef): PaymentResponseDTO
    {
        try {
            $response = Http::withHeaders([
                'authorization' => $this->serverKey,
                'content-type' => 'application/json',
            ])->post($this->baseUrl . 'query', [
                'profile_id' => $this->profileId,
                'tran_ref' => $transactionRef
            ]);

            if (!$response->successful()) {
                throw new PaymentGatewayException(
                    "Payment verification failed: " . ($response['message'] ?? 'Unknown error'),
                    $response->status()
                );
            }

            $data = $response->json();

            return new PaymentResponseDTO(
                status: $this->mapPaymentStatus($data['payment_result']['response_status'] ?? ''),
                message: $data['payment_result']['response_message'] ?? 'Payment verified',
                transactionRef: $data['tran_ref'] ?? null,
                rawResponse: $data
            );

        } catch (\Exception $e) {
            Log::error('ClickPay payment verification failed', [
                'error' => $e->getMessage(),
                'transactionRef' => $transactionRef
            ]);
            throw new PaymentGatewayException(
                'Payment verification failed: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function refundPayment(string $transactionRef, float $amount): PaymentResponseDTO
    {
        try {
            $response = Http::withHeaders([
                'authorization' => $this->serverKey,
                'content-type' => 'application/json',
            ])->post($this->baseUrl . 'request', [
                'profile_id' => $this->profileId,
                'tran_type' => 'refund',
                'tran_ref' => $transactionRef,
                'cart_amount' => $amount
            ]);

            if (!$response->successful()) {
                throw new PaymentGatewayException(
                    "Refund failed: " . ($response['message'] ?? 'Unknown error'),
                    $response->status()
                );
            }

            $data = $response->json();

            return new PaymentResponseDTO(
                status: $this->mapPaymentStatus($data['payment_result']['response_status'] ?? ''),
                message: $data['payment_result']['response_message'] ?? 'Refund processed',
                transactionRef: $data['tran_ref'] ?? null,
                rawResponse: $data
            );

        } catch (\Exception $e) {
            Log::error('ClickPay refund failed', [
                'error' => $e->getMessage(),
                'transactionRef' => $transactionRef,
                'amount' => $amount
            ]);
            throw new PaymentGatewayException(
                'Refund failed: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    public function validateCallback(array $data): bool
    {
        try {
            $signature = $data['signature'] ?? '';
            unset($data['signature']);

            ksort($data);
            $query = http_build_query($data);

            $calculatedSignature = hash_hmac('sha256', $query, $this->serverKey);

            return hash_equals($calculatedSignature, $signature);
        } catch (\Exception $e) {
            Log::error('ClickPay callback validation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    private function buildPaymentPayload(PaymentRequestDTO $request, Order $order): array
    {
        $client = $order->user;
        return [
            'profile_id' => $this->profileId,
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => $request->cartId,
            'cart_description' => $request->description,
            'cart_currency' => 'SAR',
            'cart_amount' => $request->amount,
            'callback' => route('payment.callback'),
            'return' => route('payment.status'),
            'customer_details' => [
                'name' => $client->name,
                'email' => $client->email,
                'street1' => $order->address?->address,
                'city' => '',
                'state' => '',
                'country' => 'SA',
                'ip' => request()->ip(),
            ]
        ];
    }

    private function mapPaymentStatus(string $status): string
    {
        return match ($status) {
            'A' => 'success',
            'H' => 'hold',
            'V' => 'void',
            'E' => 'error',
            'D' => 'declined',
            default => 'unknown'
        };
    }
}
