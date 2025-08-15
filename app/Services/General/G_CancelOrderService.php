<?php

namespace App\Services\General;

use App\Enums\ErrorMessageEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class G_CancelOrderService
{
    /**
     *
     * @param Order $order
     * @param array $data
     * @param User $user
     * @return JsonResponse
     */
    public function cancel(Order $order, array $data, User $user): JsonResponse
    {
        try {
            if (!in_array($order->status, [OrderStatusEnum::CANCELLED, OrderStatusEnum::REJECTED])) {
                $status = $user->hasRole(RoleEnum::CLIENT)
                    ? OrderStatusEnum::CANCELLED
                    : OrderStatusEnum::REJECTED;

                $order->update(['status' => $status, ...$data]);

                return response()->json(successResponse(message: trans(SuccessMessagesEnum::CANCELLED)));
            }

            return response()->json(errorResponse(message: trans(ErrorMessageEnum::CANCEL)), Response::HTTP_BAD_REQUEST);

        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CANCEL),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
