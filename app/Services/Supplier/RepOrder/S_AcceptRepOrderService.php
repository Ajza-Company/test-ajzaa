<?php

namespace App\Services\Supplier\RepOrder;

use App\Enums\ErrorMessageEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\RepOrderStatusEnum;
use App\Enums\SuccessMessagesEnum;
use App\Http\Resources\v1\Supplier\RepOrder\S_ShortRepOrderResource;
use App\Models\RepChat;
use App\Notifications\OrderNotification;
use App\Notifications\RepOrderNotification;
use App\Repositories\Supplier\RepOrder\Find\S_FindRepOrderInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class S_AcceptRepOrderService
{
    /**
     * Create a new instance.
     *
     * @param S_FindRepOrderInterface $findRepOrder
     */
    public function __construct(private S_FindRepOrderInterface $findRepOrder)
    {

    }

    /**
     * Create a new offer.
     *
     * @param string $rep_order_id
     * @return JsonResponse
     * @throws Throwable
     */
    public function execute(string $rep_order_id): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $repOrder = $this->findRepOrder->find(decodeString($rep_order_id));

            if ($repOrder->status != 'pending') {
                return response()->json(errorResponse(trans(ErrorMessageEnum::FOUND)));
            }

            RepChat::create([
                'rep_order_id' => $repOrder->id,
                'user1_id' => auth('api')->id(),
                'user2_id' => $repOrder->user_id
            ]);

            $repOrder->user->notify(new RepOrderNotification(
                order: $repOrder,
                type: RepOrderStatusEnum::ACCEPTED
            ));

            $repOrder->update(['status' => RepOrderStatusEnum::ACCEPTED, 'rep_id' => auth('api')->id()]);
            $repOrder->refresh();

            \DB::commit();
            return response()->json(successResponse(trans(SuccessMessagesEnum::CREATED), data: S_ShortRepOrderResource::make($repOrder->load('repChat'))));
        } catch (\Exception $ex) {
            \DB::rollBack();
            return response()->json(errorResponse(trans(ErrorMessageEnum::CREATE), $ex->getMessage()), 500);
        }
    }
}
