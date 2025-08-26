<?php

namespace App\Http\Controllers;

use App\Services\Payment\InterPayTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InterPayController extends Controller
{
    private InterPayTokenService $tokenService;

    public function __construct(InterPayTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    /**
     * Show the InterPay checkout page with generated tokens
     */
    public function showCheckout(Request $request)
    {
        try {
            // Generate tokens for the order
            $tokenData = $this->tokenService->generateTestToken();
            
            if (!$tokenData['success']) {
                Log::channel('interpay')->error('Failed to generate tokens for checkout', $tokenData);
                return back()->withErrors(['payment' => 'Failed to initialize payment: ' . $tokenData['message']]);
            }

            return view('payment.interpay-checkout', [
                'amount' => '1.01',
                'token' => $tokenData['token'],
                'authToken' => $tokenData['auth_token'],
                'orderId' => 'TEST_' . time()
            ]);

        } catch (\Exception $e) {
            Log::channel('interpay')->error('Exception in checkout', [
                'error' => $e->getMessage()
            ]);
            
            return back()->withErrors(['payment' => 'Payment system error. Please try again.']);
        }
    }

    /**
     * Generate tokens for a specific order
     */
    public function generateTokens(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'string|in:SAR',
            'customer.name' => 'required|string|max:255',
            'customer.email' => 'required|email',
            'customer.address1' => 'required|string|max:255',
            'customer.address2' => 'nullable|string|max:255',
            'customer.city' => 'required|string|max:255',
            'customer.country_code' => 'required|string|size:3',
            'ecommerce_order_id' => 'nullable|string|max:255'
        ]);

        try {
            $orderData = [
                'amount' => $request->amount,
                'currency' => $request->currency ?? 'SAR',
                'ecommerce_order_id' => $request->ecommerce_order_id,
                'customer' => [
                    'name' => $request->input('customer.name'),
                    'email' => $request->input('customer.email'),
                    'address1' => $request->input('customer.address1'),
                    'address2' => $request->input('customer.address2'),
                    'city' => $request->input('customer.city'),
                    'country_code' => $request->input('customer.country_code'),
                ]
            ];

            $tokenData = $this->tokenService->generateToken($orderData);

            if ($tokenData['success']) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $tokenData['token'],
                        'auth_token' => $tokenData['auth_token'],
                        'status' => $tokenData['status'],
                        'message' => $tokenData['message']
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $tokenData['message']
            ], 400);

        } catch (\Exception $e) {
            Log::channel('interpay')->error('Exception generating tokens', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Show checkout with custom order data
     */
    public function showCustomCheckout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_address' => 'required|string|max:255',
            'customer_city' => 'required|string|max:255',
            'customer_country' => 'required|string|size:3',
        ]);

        try {
            $orderData = [
                'amount' => $request->amount,
                'currency' => 'SAR',
                'ecommerce_order_id' => 'ORDER_' . time(),
                'customer' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'address1' => $request->customer_address,
                    'address2' => '',
                    'city' => $request->customer_city,
                    'country_code' => $request->customer_country,
                ]
            ];

            $tokenData = $this->tokenService->generateToken($orderData);
            
            if (!$tokenData['success']) {
                return back()->withErrors(['payment' => 'Failed to initialize payment: ' . $tokenData['message']]);
            }

            return view('payment.interpay-checkout', [
                'amount' => $request->amount,
                'token' => $tokenData['token'],
                'authToken' => $tokenData['auth_token'],
                'orderId' => $orderData['ecommerce_order_id']
            ]);

        } catch (\Exception $e) {
            Log::channel('interpay')->error('Exception in custom checkout', [
                'error' => $e->getMessage()
            ]);
            
            return back()->withErrors(['payment' => 'Payment system error. Please try again.']);
        }
    }
}
