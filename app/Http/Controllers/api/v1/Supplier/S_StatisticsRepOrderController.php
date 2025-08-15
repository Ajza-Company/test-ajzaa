<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Models\RepOffer;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Supplier\User\Find\S_FindUserInterface;
use Illuminate\Http\Request;

class S_StatisticsRepOrderController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param S_FindUserInterface $findUser
     */
    public function __construct(private S_FindUserInterface $findUser)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        return response()->json($this->getStatistics($user));
    }

    /**
     * Get User statistics.
     *
     * @param mixed $user
     * @return array
     */
    private function getStatistics(User $user): array
    {
        return [
            'allOrdersCount' => $this->getAllOrdersCount($user),
            'ordersAmountToday' => round( $this->ordersAmountToday($user), 2),
            'ordersAmounts' => round($this->getOrdersAmount($user), 2),
            'ajzaAmount' => round($this->getAjzaAmount($user), 2),
        ];
    }

    /**
     * Get all orders count.
     *
     * @param mixed $user
     * @return int
     */
    private function getAllOrdersCount(User $user): int
    {
        return $user->repVendorOrders()->dateRangeFilter(request())->count();
    }

    /**
     * Get pending orders count.
     *
     * @param mixed $user
     * @return int
     */
    private function ordersAmountToday(User $user): float
    {
        $query = RepOffer::whereHas('order', function ($query) use ($user) {
            $query->where('rep_id', $user->id)
                ->whereDate('created_at', now()->format('Y-m-d'));
        })
            ->where('status', 'accepted');

        return $query->sum('price');
    }


    /**
     * Get orders amount today.
     *
     * @param mixed $user
     * @return float
     */
    private function getOrdersAmount(User $user): float
    {
        // Get all rep orders with the applied statistics filter and date range filter
        $repOrders = $user->repVendorOrders()
            ->dateRangeFilter(request())
            ->get();

        // Initialize the total
        $total = 0;

        // Loop through each rep order and sum up the accepted offers
        foreach ($repOrders as $repOrder) {
            $total += $repOrder->offers()->where('status', 'accepted')->sum('price');
        }

        return (float) $total;
    }

    /**
     * Get ajza amount.
     *
     * @param mixed $user
     * @return float
     */
    private function getAjzaAmount(User $user): float
    {
        $ajzaPercentage = json_decode(Setting::latest()->first()->setting);

        return $user->repVendorOrders()
            ->dateRangeFilter(request())
            ->get()
            ->sum(function ($repOrder) use ($ajzaPercentage) {
                $acceptedOffer = $repOrder->offers()->where('status', 'accepted')->first();
                if ($acceptedOffer) {
                    return $acceptedOffer->price * ($ajzaPercentage->rep_order_percentage / 100);
                }
                return 0;
            });
    }
}
