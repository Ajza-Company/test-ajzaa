<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\OrderDeliveryMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Events\v1\Frontend\F_OrderCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TransactionAttempt;
use App\Services\Delivery\OtoGateway;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class F_PaymentCallbackController extends Controller
{
    public function __construct(private OtoGateway $otoGateway)
    {

    }

    /**
     * Handle the incoming request.
     * @throws ConnectionException
     */
    public function __invoke(Request $request)
    {
        \Log::info('payment callback: '.json_encode($request->all()));

        if ($request->has('payment_result')) {
            if ($request->payment_result['response_status'] == 'A') {
                $transaction = TransactionAttempt::findOrFail(decodeString($request->cart_id));
                $transaction = tap($transaction)->update([
                    'payment_status' => true,
                    'status' => 'paid',
                    'paymob_callback' => json_encode($request->all()),
                    'paymob_transaction_id' => $request->tran_ref
                ]);

                broadcast(new F_OrderCreatedEvent($transaction->order))->toOthers();

                $transaction->order->update([
                    'status' => OrderStatusEnum::ACCEPTED
                ]);

                // decrement product quantity
                foreach ($transaction->order->orderProducts as $orderProduct) {
                    $orderProduct->storeProduct->decrement('quantity', $orderProduct->quantity);
                }

                // create delivery shipment
                if ($transaction->order->delivery_method == OrderDeliveryMethodEnum::DELIVERY) {
                    // $shipment = $this->otoGateway->createShipment($transaction->order);
                    //\Log::info('shipment: '.json_encode($shipment));
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Payment completed successfully.',
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Payment failed.',
            ], 400);
        }
        return response()->json([
            'status' => false,
            'message' => 'Error Response',
        ], 400);
    }
}
