<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;

class S_StatisticsController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_FindStoreInterface $findStore
     */
    public function __construct(private S_FindStoreInterface $findStore)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $store_id)
    {
        $store = $this->findStore->find(decodeString($store_id));
        return response()->json($this->getStatistics($store));
    }

    /**
     * Get store statistics.
     *
     * @param mixed $store
     * @return array
     */
    private function getStatistics(Store $store): array
    {
        return [
            'allOrdersCount' => $this->getAllOrdersCount($store),
            'ordersAmountToday' => round( $this->ordersAmountToday($store), 2),
            'ordersAmounts' => round($this->getOrdersAmount($store), 2),
            'ajzaAmount' => round($this->getAjzaAmount($store), 2),
        ];
    }

    /**
     * Get all orders count.
     *
     * @param mixed $store
     * @return int
     */
    private function getAllOrdersCount($store): int
    {
        return $store->orders()->statisticsFilter(request())->count();
    }

    /**
     * Get pending orders count.
     *
     * @param mixed $store
     * @return int
     */
    private function ordersAmountToday(Store $store): int
    {
        return $store->orders()->whereDate('created_at', now()->format('Y-m-d'))->sum('amount');
    }

    /**
     * Get orders amount today.
     *
     * @param mixed $store
     * @return float
     */
    private function getOrdersAmount(Store $store): float
    {
        return (float) $store->orders()->statisticsFilter(request())->sum('amount');
    }

    /**
     * Get ajza amount.
     *
     * @param mixed $store
     * @return float
     */
    private function getAjzaAmount(Store $store): float
    {
        return $store->orders()->statisticsFilter(request())->get()->sum(function ($order) {
            return $order->amount * ($order->ajza_percentage / 100);
        });
    }
}
