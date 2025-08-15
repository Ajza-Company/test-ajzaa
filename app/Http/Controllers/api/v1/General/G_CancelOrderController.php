<?php

namespace App\Http\Controllers\api\v1\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\General\Order\G_CancelOrderRequest;
use App\Repositories\Frontend\Order\Find\F_FindOrderInterface;
use App\Services\General\G_CancelOrderService;
use Illuminate\Http\Request;

class G_CancelOrderController extends Controller
{
    public function __construct(private G_CancelOrderService $cancelOrder, private F_FindOrderInterface $findOrder)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $order_id, G_CancelOrderRequest $request)
    {
        $order = $this->findOrder->find(decodeString($order_id));
        return $this->cancelOrder->cancel($order, $request->validated(), auth('api')->user());
    }
}
