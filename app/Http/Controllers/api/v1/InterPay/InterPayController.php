<?php

namespace App\Http\Controllers\api\v1\InterPay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InterPayController extends Controller
{
    /**
     * Generate payment token for InterPay
     */
    public function generateToken(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'order_id' => 'required|string',
                'customer_name' => 'required|string',
                'customer_email' => 'required|email',
                'customer_address1' => 'required|string',
                'customer_address2' => 'required|string',
                'customer_city' => 'required|string',
                'customer_country_code' => 'required|string',
            ]);

            // InterPay API configuration
            $interpayConfig = [
                'base_url' => env('INTERPAY_BASE_URL', 'https://interpayapimanagement.azure-api.net'),
                'public_key' => env('INTERPAY_PUBLIC_KEY'),
                'secret_key' => env('INTERPAY_SECRET_KEY'),
            ];

            // Generate payment token
            $tokenData = [
                'amount' => $request->amount,
                'currency' => 'SAR',
                'ecommerceOrderId' => $request->order_id,
                'customer' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'address1' => $request->customer_address1,
                    'address2' => $request->customer_address2,
                    'city' => $request->customer_city,
                    'countryCode' => $request->customer_country_code,
                ]
            ];

            // Call InterPay API to generate token
            \Log::info('InterPay API Request', [
                'url' => $interpayConfig['base_url'] . '/api/v1/Order/Create',
                'data' => $tokenData,
                'config' => $interpayConfig
            ]);
            
            $response = $this->callInterPayAPI('POST', '/api/v1/Order/Create', $tokenData, $interpayConfig);
            
            \Log::info('InterPay API Response', [
                'response' => $response
            ]);

            if ($response['success'] && $response['data']['status'] === '1') {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $response['data']['token'],
                        'authToken' => $response['data']['authToken'],
                        'status' => $response['data']['status'],
                        'message' => $response['data']['message'],
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payment token',
                'error' => $response['data']['message'] ?? 'Unknown error'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating payment token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle InterPay callback
     */
    public function callback(Request $request): JsonResponse
    {
        try {
            // Verify InterPay callback signature
            $signature = $request->header('X-InterPay-Signature');
            $payload = $request->getContent();

            if (!$this->verifyCallbackSignature($signature, $payload)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid callback signature'
                ], 400);
            }

            // Process payment result
            $paymentData = $request->all();
            
            // Update order status based on payment result
            $this->processPaymentResult($paymentData);

            return response()->json([
                'success' => true,
                'message' => 'Payment callback processed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment callback',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Call InterPay API
     */
    private function callInterPayAPI(string $method, string $endpoint, array $data, array $config): array
    {
        $url = $config['base_url'] . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'PublicKey: ' . $config['public_key'],
            'SecretKey: ' . $config['secret_key'],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, $method === 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $error
            ];
        }

        $responseData = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'data' => $responseData
            ];
        }

        return [
            'success' => false,
            'message' => $responseData['message'] ?? 'API request failed',
            'http_code' => $httpCode
        ];
    }

    /**
     * Verify callback signature
     */
    private function verifyCallbackSignature(string $signature, string $payload): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, env('INTERPAY_WEBHOOK_SECRET', ''));
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Process payment result
     */
    private function processPaymentResult(array $paymentData): void
    {
        // Update order status based on payment result
        $orderId = $paymentData['order_id'] ?? null;
        $status = $paymentData['status'] ?? null;
        $transactionId = $paymentData['transaction_id'] ?? null;

        if ($orderId && $status) {
            // Update order in database
            // This depends on your order model structure
            \Log::info('Payment result processed', [
                'order_id' => $orderId,
                'status' => $status,
                'transaction_id' => $transactionId
            ]);
        }
    }
}
