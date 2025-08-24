<?php

namespace App\Http\Controllers\api\v1\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\InterPayGateway;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InterPayCallbackController extends Controller
{
    public function __construct(private InterPayGateway $interPayGateway)
    {
    }

    /**
     * Handle InterPay payment callback
     */
    public function handle(Request $request)
    {
        try {
            Log::info('InterPay callback received', $request->all());

            // Get the response parameter from callback
            $responseParam = $request->get('response');
            
            if (!$responseParam) {
                Log::error('InterPay callback missing response parameter');
                return response()->json(['error' => 'Missing response parameter'], 400);
            }

            // Decode the response parameter
            $responseData = json_decode(urldecode($responseParam), true);
            
            if (!$responseData) {
                Log::error('InterPay callback response decode failed');
                return response()->json(['error' => 'Invalid response data'], 400);
            }

            Log::info('InterPay callback decoded data', $responseData);

            // Validate the callback
            if (!$this->interPayGateway->validateCallback($responseData)) {
                Log::error('InterPay callback validation failed', $responseData);
                return response()->json(['error' => 'Callback validation failed'], 400);
            }

            // Get order from OrderId
            $order = Order::where('id', $responseData['OrderId'])->first();
            
            if (!$order) {
                Log::error('Order not found for InterPay callback', ['order_id' => $responseData['OrderId']]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Update order status based on payment result
            if ($responseData['ResponseCode'] === '00' && $responseData['Status'] === '1') {
                // Payment successful
                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'completed',
                    'payment_method' => 'interpay',
                    'transaction_id' => $responseData['TransactionId'],
                    'paid_at' => now()
                ]);

                Log::info('Order payment completed successfully', [
                    'order_id' => $order->id,
                    'transaction_id' => $responseData['TransactionId']
                ]);

                // You can add additional logic here like:
                // - Send confirmation email
                // - Update inventory
                // - Trigger webhooks
            } else {
                // Payment failed
                $order->update([
                    'payment_status' => 'failed',
                    'payment_method' => 'interpay',
                    'transaction_id' => $responseData['TransactionId'] ?? null
                ]);

                Log::warning('Order payment failed', [
                    'order_id' => $order->id,
                    'response_code' => $responseData['ResponseCode'],
                    'status' => $responseData['Status']
                ]);
            }

            // Return success response to InterPay
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('InterPay callback processing failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }
}
