<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\DTOs\PaymentRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\Order\F_PayRequest;
use App\Models\TransactionAttempt;
use App\Repositories\Frontend\Order\Find\F_FindOrderInterface;
use App\Services\Payment\ClickPayGateway;
use App\Services\Payment\PaymentService;
use Exception;
use Illuminate\Http\Request;

class F_PayController extends Controller
{
    /**
     * show a new instance.
     *
     * @param F_FindOrderInterface $findOrder
     */
    public function __construct(private F_FindOrderInterface $findOrder)
    {
    }

    /**
     * Handle the incoming request.
     * @throws Exception
     */
    public function __invoke(string $order_id, F_PayRequest $request)
    {
        $order = $this->findOrder->find(decodeString($order_id));



        $gateway = match(config('services.payment.default')) {
            'clickpay' => new ClickPayGateway(),
            default => throw new Exception('Invalid gateway'),
        };

        $paymentService = new PaymentService($gateway);
        $result = $paymentService->createPayment(
            new PaymentRequestDTO(amount: $request->validated()['amount'], description: 'Order Payment', cartId: $order->id)
        );

        return response()->json([
            'status' => $result->status,
            'message' => $result->message,
            'redirectUrl' => $result->redirectUrl
        ]);
    }
}
