<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class InterPayController extends Controller
{
    /**
     * Handle InterPay payment callback
     */
    public function callback(Request $request)
    {
        try {
            Log::channel('InterPay')->info('Callback received', $request->all());

            // Get the response parameter from callback
            $responseParam = $request->get('response');
            
            if (!$responseParam) {
                Log::channel('InterPay')->error('Missing response parameter');
                return response()->json(['error' => 'Missing response parameter'], 400);
            }

            // Decode the response parameter
            $responseData = json_decode(urldecode($responseParam), true);
            
            if (!$responseData) {
                Log::channel('InterPay')->error('Response decode failed');
                return response()->json(['error' => 'Invalid response data'], 400);
            }

            Log::channel('InterPay')->info('Decoded callback data', $responseData);

            // Check if payment was successful
            if ($responseData['ResponseCode'] === '00' && $responseData['Status'] === '1') {
                // Payment successful
                $order = Order::where('id', $responseData['OrderId'])->first();
                
                if ($order) {
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'completed',
                        'payment_method' => 'interpay',
                        'transaction_id' => $responseData['TransactionId'],
                        'paid_at' => now()
                    ]);

                    Log::channel('InterPay')->info('Order payment completed', [
                        'order_id' => $order->id,
                        'transaction_id' => $responseData['TransactionId']
                    ]);
                }

                return response()->json(['status' => 'success', 'message' => 'Payment completed']);
            } else {
                // Payment failed
                Log::channel('InterPay')->warning('Payment failed', [
                    'response_code' => $responseData['ResponseCode'],
                    'status' => $responseData['Status'],
                    'message' => $responseData['Message'] ?? 'Unknown error'
                ]);

                return response()->json(['status' => 'failed', 'message' => 'Payment failed']);
            }

        } catch (\Exception $e) {
            Log::channel('InterPay')->error('Callback processing failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }

    /**
     * Generate InterPay tokens for payment
     */
    public function generateTokens(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'currency' => 'string|in:SAR',
                'customer.name' => 'required|string|max:255',
                'customer.email' => 'required|email',
                'customer.address1' => 'required|string|max:255',
                'customer.city' => 'required|string|max:255',
                'customer.country_code' => 'required|string|size:3',
                'ecommerce_order_id' => 'nullable|string|max:255'
            ]);

            // Get InterPay credentials
            $publicKey = config('services.payment.interpay.public_key');
            $secretKey = config('services.payment.interpay.secret_key');
            $apiBaseUrl = config('services.payment.interpay.api_base_url', 'https://interpayapimanagement.azure-api.net');

            if (!$publicKey || !$secretKey) {
                throw new \Exception('InterPay credentials not configured');
            }

            // Prepare order data for InterPay API
            $orderData = [
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'SAR',
                'ecommerceOrderId' => $request->ecommerce_order_id ?? 'PAY_' . time(),
                'customer' => [
                    'name' => $request->input('customer.name'),
                    'email' => $request->input('customer.email'),
                    'address1' => $request->input('customer.address1'),
                    'city' => $request->input('customer.city'),
                    'countryCode' => $request->input('customer.country_code'),
                ]
            ];

            Log::channel('InterPay')->info('Generating InterPay tokens', [
                'order_data' => $orderData,
                'api_url' => $apiBaseUrl . '/api/v1/Order/Create'
            ]);

            // Call InterPay API to generate tokens
            $response = Http::withHeaders([
                'PublicKey' => $publicKey,
                'SecretKey' => $secretKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($apiBaseUrl . '/api/v1/Order/Create', $orderData);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::channel('InterPay')->info('InterPay tokens generated successfully', [
                    'order_id' => $orderData['ecommerceOrderId'],
                    'response_keys' => array_keys($data),
                    'token_field' => $data['Token'] ?? $data['token'] ?? 'NOT_FOUND',
                    'auth_token_field' => $data['authToken'] ?? $data['AuthToken'] ?? 'NOT_FOUND'
                ]);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $data['Token'] ?? $data['token'] ?? '',
                        'auth_token' => $data['authToken'] ?? $data['AuthToken'] ?? '',
                        'status' => $data['status'] ?? '0',
                        'message' => $data['message'] ?? 'Success'
                    ]
                ]);
            }

            // Log failed token generation
            Log::channel('InterPay')->error('Failed to generate InterPay tokens', [
                'status_code' => $response->status(),
                'response' => $response->body(),
                'order_id' => $orderData['ecommerceOrderId']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate tokens: HTTP ' . $response->status(),
                'response_body' => $response->body()
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::channel('InterPay')->warning('Token generation validation failed', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::channel('InterPay')->error('Exception generating InterPay tokens', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ], 500);
        }
    }
}
