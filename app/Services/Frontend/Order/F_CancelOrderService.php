<?php

namespace App\Services\Frontend\Order;

use App\Enums\ErrorMessageEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_CancelOrderService
{
    /**
     *
     * @param array $data
     * @param Order $order
     * @return JsonResponse
     */
    public function cancel(array $data, Order $order): JsonResponse
    {
        try {
            if ($order->status === OrderStatusEnum::CANCELLED) {
                return response()->json(errorResponse(
                    message: trans(ErrorMessageEnum::CANCEL)),
                    Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $order->update([
                'status' => OrderStatusEnum::CANCELLED,
                ...$data
            ]);
            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CANCELLED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
