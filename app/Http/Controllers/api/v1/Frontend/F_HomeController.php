<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Order\F_ShortOrderResource;
use Illuminate\Http\Request;

class F_HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth('api')->user();
        $notReviewedOrders = $user
            ->orders()
            ->where('status', OrderStatusEnum::COMPLETED)
            ->with('store')
            ->whereDoesntHave('review')
            ->adaptivePaginate();

        $notReadNotifications = $user
            ->notifications()
            ->where('read_at', null)
            ->count();

        return response()->json([
            'data' => [
                'notReviewedOrders' => F_ShortOrderResource::collection($notReviewedOrders),
                'notReadNotifications' => $notReadNotifications
            ]
        ]);
    }
}
