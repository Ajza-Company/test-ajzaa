<?php

namespace App\Services\Frontend\StoreReview;

use App\Enums\ErrorMessageEnum;
use App\Enums\SuccessMessagesEnum;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Frontend\StoreReview\Create\F_CreateStoreReviewInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class F_SubmitStoreReviewService
{
    /**
     * Create a new instance.
     *
     * @param F_CreateStoreReviewInterface $createStoreReview
     */
    public function __construct(private F_CreateStoreReviewInterface $createStoreReview)
    {

    }

    /**
     *
     * @param Order $order
     * @param int $user_id
     * @param array $data
     *
     * @return JsonResponse
     */
    public function create(Order $order, int $user_id, array $data): JsonResponse
    {
        try {
            $data = [
                'user_id' => $user_id,
                'order_id' => $order->id,
                'store_id' => $order->store_id,
                ...$data
            ];
            $this->createStoreReview->create($data);

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CREATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
