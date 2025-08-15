<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Enums\OrderStatusEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Order\F_OrderResource;
use App\Http\Resources\v1\Frontend\Order\F_ShortOrderResource;
use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Repositories\Supplier\Store\Find\S_FindStoreInterface;

class A_StatisticsController extends Controller
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
     * Get admin statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => $this->getStatistics()
        ]);
    }

    /**
     * Get store statistics.
     *
     * @return array
     */
    private function getStatistics(): array
    {
        return [
            'allOrdersCount' => $this->getAllOrdersCount(),
            'ordersAmountToday' => round($this->ordersAmountToday(), 2),
            'ordersAmounts' => round($this->getOrdersAmount(), 2),
            'ajzaAmount' => round($this->getAjzaAmount(), 2),
            'companies_count' => Company::count(),
            'stores_count' => Store::count(),
            'users' => [
                'total' => User::whereHas('roles')->count(),
                'client' => User::role(RoleEnum::CLIENT)->count(),
                'supplier' => User::role(RoleEnum::SUPPLIER)->count(),
                'admin' => User::role(RoleEnum::ADMIN)->count(),
                'representative' => User::role(RoleEnum::REPRESENTATIVE)->count()
            ],
            'products_count' => Product::count(),
            'orders' => [
                'total' => Order::count(),
                'canceled' => Order::where('status', OrderStatusEnum::CANCELLED)->count(),
                'completed' => Order::where('status', OrderStatusEnum::COMPLETED)->count(),
                'rejected' => Order::where('status', OrderStatusEnum::REJECTED)->count(),
            ]
        ];
    }

    private function getAllOrdersCount()
    {
        return Order::statisticsFilter(request())->count();
    }

    private function ordersAmountToday()
    {
        return Order::whereDate('created_at', now()->format('Y-m-d'))->sum('amount');
    }

    private function getOrdersAmount(): float
    {
        return (float) Order::statisticsFilter(request())->sum('amount');
    }

    private function getAjzaAmount(): float
    {
        return Order::statisticsFilter(request())->get()->sum(function ($order) {
            return $order->amount * ($order->ajza_percentage / 100);
        });
    }

    public function orders()
    {
        $orders = Order::statisticsFilter(request())->adaptivePaginate();

        return F_ShortOrderResource::collection($orders);
    }
}
