<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
