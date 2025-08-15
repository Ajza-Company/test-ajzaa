<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\RepOrder\S_TrackRepOrderRequest;
use App\Repositories\Supplier\RepOrder\Find\S_FindRepOrderInterface;
use App\Services\Supplier\RepOrder\S_TrackRepOrderService;
use Illuminate\Http\Request;
use Throwable;

class S_LocationTrackingController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_TrackRepOrderService $trackRepOrder
     */
    public function __construct(private S_TrackRepOrderService $trackRepOrder , private S_FindRepOrderInterface $findOrder)
    {

    }

    /**
     * Handle the incoming request.
     * @throws Throwable
     */
    public function __invoke(S_TrackRepOrderRequest $request, string $rep_order_id)
    {
        return $this->trackRepOrder->create($rep_order_id, $request->validated());
    }

    public function track(string $rep_order_id)
    {
        $order = $this->findOrder->find(decodeString($rep_order_id));

        $trackingFirst = $order->tracking()->first();
        $trackingLast = $order->address;
        $trackingCurrent = $order->tracking()->latest()->first();

        return[
            'first' => [
                'rep_order_id' => $order->id,
                'latitude' => $trackingFirst?->latitude,
                'longitude' => $trackingFirst?->longitude
            ],
            'last' => [
                'rep_order_id' => $order->id,
                'latitude' => $trackingLast?->latitude,
                'longitude' => $trackingLast?->longitude
            ],
            'current' => [
                'rep_order_id' => $order->id,
                'latitude' => $trackingCurrent?->latitude,
                'longitude' => $trackingCurrent?->longitude
            ]
        ];
    }

}
