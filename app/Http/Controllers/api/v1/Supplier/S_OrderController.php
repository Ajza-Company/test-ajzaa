<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Supplier\Order\S_TakeActionRequest;
use App\Http\Resources\v1\Supplier\Order\S_OrderResource;
use App\Http\Resources\v1\Supplier\Order\S_ShortOrderResource;
use App\Repositories\Supplier\Order\Find\S_FindOrderInterface;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;
use App\Services\Supplier\Order\S_TakeActionService;
use Illuminate\Http\Request;

class S_OrderController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_FindStoreInterface $findStore
     * @param S_FindOrderInterface $findOrder
     * @param S_TakeActionService $takeAction
     */
    public function __construct(
        private S_FindStoreInterface $findStore,
        private S_FindOrderInterface $findOrder,
        private S_TakeActionService $takeAction)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function orders(string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        $orders = $store->orders()->with(['orderProducts' => ['storeProduct']])->ordersFilter(\request())->latest()->adaptivePaginate();
        return S_ShortOrderResource::collection($orders);
    }

    /**
     * Display a listing of the resource.
     */
    public function details(string $order_id)
    {
        $order = $this->findOrder->find(decodeString($order_id));
        return S_OrderResource::make($order->load('address'));
    }

    /**
     * Display a listing of the resource.
     */
    public function takeAction(S_TakeActionRequest $request, string $order_id)
    {
        $order = $this->findOrder->find(decodeString($order_id));
        return $this->takeAction->execute($request->validated(), $order);
    }
}
