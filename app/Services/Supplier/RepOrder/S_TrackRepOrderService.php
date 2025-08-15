<?php

namespace App\Services\Supplier\RepOrder;

use App\Enums\ErrorMessageEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\RepOrderStatusEnum;
use App\Enums\SuccessMessagesEnum;
use App\Events\v1\General\G_SupportMessageSent;
use App\Events\v1\Supplier\S_TrackRepOrderEvent;
use App\Http\Resources\v1\Supplier\RepOrder\S_ShortRepOrderResource;
use App\Models\RepChat;
use App\Notifications\OrderNotification;
use App\Notifications\RepOrderNotification;
use App\Repositories\Supplier\RepOrder\Find\S_FindRepOrderInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class S_TrackRepOrderService
{
    /**
     * Create a new instance.
     *
     * @param S_FindRepOrderInterface $findOrder
     */
    public function __construct(private S_FindRepOrderInterface $findOrder)
    {

    }

    /**
     *
     * @param string $rep_order_id
     * @param array $data
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(string $rep_order_id, array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $order = $this->findOrder->find(decodeString($rep_order_id));
            $order->tracking()->create([
                'rep_id' => auth('api')->id(),
                ...$data
            ]);

            broadcast(new S_TrackRepOrderEvent($order))->toOthers();

            \DB::commit();
            return response()->json(successResponse(trans(SuccessMessagesEnum::CREATED)));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE), $ex->getMessage()), 500);
        }
    }
}
