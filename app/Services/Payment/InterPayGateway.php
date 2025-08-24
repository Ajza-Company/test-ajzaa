<?php

namespace App\Services\Payment;

use App\DTOs\PaymentRequestDTO;
use App\DTOs\PaymentResponseDTO;
use App\Exceptions\PaymentGatewayException;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InterPayGateway implements PaymentGatewayInterface
{
    private string $baseUrl;
    private string $publicKey;
    private string $secretKey;

    public function __construct()
    {
        $this->baseUrl = 'https://ecomspghostedpage.softpos-ksa.com/';
        $this->publicKey = config('services.payment.interpay.public_key');
        $this->secretKey = config('services.payment.interpay.secret_key');
    }

    /**
     * @throws PaymentGatewayException
     */
    public function createPayment(PaymentRequestDTO $request, Order $order): PaymentResponseDTO
    {
        try {
            // InterPay uses hosted checkout page, so we return redirect URL
            $checkoutUrl = $this->buildCheckoutUrl($request, $order);
            
            Log::info('InterPay checkout URL created', ['url' => $checkoutUrl]);

            return new PaymentResponseDTO(
                status: 'pending',
                message: 'Redirect to InterPay checkout required',
                redirectUrl: $checkoutUrl,
                transactionRef: $request->cartId,
                rawResponse: ['checkout_url' => $checkoutUrl]
            );

        } catch (\Exception $e) {
            Log::error('InterPay payment creation failed', [
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
            // For InterPay, we verify through callback data
            // This method is called when processing callback
            $data = $transactionRef;
            
            if (!isset($data['ResponseCode']) || $data['ResponseCode'] !== '00') {
                throw new PaymentGatewayException(
                    "Payment verification failed: " . ($data['Message'] ?? 'Unknown error')
                );
            }

            return new PaymentResponseDTO(
                status: 'success',
                message: $data['Message'] ?? 'Payment verified',
                transactionRef: $data['TransactionId'] ?? null,
                rawResponse: $data
            );

        } catch (\Exception $e) {
            Log::error('InterPay payment verification failed', [
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
            // InterPay refund implementation would go here
            // For now, return not implemented
            throw new PaymentGatewayException(
                'Refund not implemented for InterPay yet'
            );

        } catch (\Exception $e) {
            Log::error('InterPay refund failed', [
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
            // InterPay callback validation
            // Check if required fields exist
            $requiredFields = ['Id', 'OrderId', 'ResponseCode', 'Status'];
            
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    Log::warning('InterPay callback missing required field', ['field' => $field]);
                    return false;
                }
            }

            // Check if payment was successful
            if ($data['ResponseCode'] !== '00' || $data['Status'] !== '1') {
                Log::warning('InterPay callback indicates failed payment', $data);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('InterPay callback validation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return false;
        }
    }

    private function buildCheckoutUrl(PaymentRequestDTO $request, Order $order): string
    {
        $client = $order->user;
        
        // Build the checkout URL with parameters
        $params = [
            'amount' => $request->amount,
            'order_id' => $request->cartId,
            'customer_name' => $client->name,
            'customer_email' => $client->email,
            'callback_url' => route('payment.interpay.callback'),
            'return_url' => route('payment.status'),
            'currency' => 'SAR'
        ];

        return $this->baseUrl . 'checkout?' . http_build_query($params);
    }

    private function mapPaymentStatus(string $status): string
    {
        return match ($status) {
            '1' => 'success',
            '0' => 'failed',
            '2' => 'pending',
            default => 'unknown'
        };
    }
}
