<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Supplier\RepOrder\S_ShortRepOrderResource;
use App\Models\RepChat;
use App\Models\RepOrder;
use App\Repositories\Supplier\RepOrder\Find\S_FindRepOrderInterface;
use App\Services\Supplier\RepOrder\S_AcceptRepOrderService;
use Illuminate\Http\Request;

class S_RepOrderController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_AcceptRepOrderService $acceptRepOrder
     */
    public function __construct(private S_AcceptRepOrderService $acceptRepOrder)
    {

    }

    /**
     * Display a listing of the resource.
     * @throws \Throwable
     */
    public function accept(string $rep_order_id)
    {
        return $this->acceptRepOrder->execute($rep_order_id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function orders()
    {
        return S_ShortRepOrderResource::collection(RepOrder::whereDoesntHave('repChats')->whereStatus('pending')->latest()->adaptivePaginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function allOrders()
    {
        return S_ShortRepOrderResource::collection(
            RepOrder::whereRelation('repChat', 'user1_id', auth('api')->id())->with(['repChat', 'address'])
                ->filter(\request())
                ->latest()
                ->adaptivePaginate());
    }
}
