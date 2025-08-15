<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\ErrorMessageEnum;
use App\Enums\MessageTypeEnum;
use App\Enums\RepOrderStatusEnum;
use App\Enums\SuccessMessagesEnum;
use App\Events\v1\General\G_RepMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Frontend\RepOrder\F_CreateRepOrderRequest;
use App\Http\Resources\v1\Frontend\RepOrder\F_ShortRepOrderResource;
use App\Models\RepChatMessage;
use App\Models\RepOrder;
use App\Services\Frontend\RepOrder\F_CreateRepOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class F_RepOrderController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_CreateRepOrderService $createRepOrder
     */
    public function __construct(private F_CreateRepOrderService $createRepOrder)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function createOrder(F_CreateRepOrderRequest $request)
    {
        return $this->createRepOrder->create(auth('api')->user(), $request->validated());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function orders()
    {
        return F_ShortRepOrderResource::collection(
            auth('api')->user()->repOrders()->with(['repChats'])->adaptivePaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkIfAccepted(string $order_id)
    {
        try {
            $order = RepOrder::findOrFail(decodeString($order_id));

            return response()->json([
                'accepted' => $order->status == RepOrderStatusEnum::ACCEPTED,
                'status' => $order->status
            ]);
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::CONNECT),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function cancelOrder(string $order_id)
    {
        \Log::info('cancel rep order');
        try {
            $order = RepOrder::findOrFail(decodeString($order_id));
            \Log::info('cancel rep order: ' . json_encode($order));

            $order->update([
                'status' => RepOrderStatusEnum::CANCELLED
            ]);

            if ($order->repChat) {
                $message = $order->repChat->messages()->create([
                    'sender_id' => auth('api')->id(),
                    'message_type' => MessageTypeEnum::CANCELLED
                ]);

                broadcast(new G_RepMessageSent($message))->toOthers();
            }

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     */
    public function orderDelivered(string $order_id)
    {
        try {
            $order = RepOrder::findOrFail(decodeString($order_id));

            $message = new RepChatMessage([
                'sender_id' => auth()->id(),
                'message_type' => MessageTypeEnum::ENDED
            ]);

            $order->repChat->messages()->save($message);
            $order->update([
                'status' => RepOrderStatusEnum::ENDED
            ]);
            $message->load(['sender']);

            broadcast(new G_RepMessageSent($message))->toOthers();

            return response()->json(successResponse(message: trans(SuccessMessagesEnum::UPDATED)));
        } catch (\Exception $ex) {
            return response()->json(errorResponse(
                message: trans(ErrorMessageEnum::UPDATE),
                error: $ex->getMessage()),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function viewInvoice(string $chat_message_id)
    {
        $message = RepChatMessage::findOrFail(decodeString($chat_message_id));

        if (!$message->is_invoice) {
            return response()->json(errorResponse(message: trans(ErrorMessageEnum::FOUND)), Response::HTTP_NOT_FOUND);
        }

        $offer = $message->chat?->order?->offers()->where('status', 'accepted')->first();
        $deliveryPrice = ajzaSetting()->delivery_initial_cost_rep_order;
        $tax = 10;
        $total = $offer?->price + $deliveryPrice + $tax;

        return response()->json([
            'data' => [
                'invoice' => getFullUrl($message->attachment),
                'price' => $offer?->price,
                'delivery_price' => $deliveryPrice,
                'tax' => $tax,
                'total' => $total
            ]
        ]);
    }
}
