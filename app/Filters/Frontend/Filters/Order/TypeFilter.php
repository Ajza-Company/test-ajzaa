<?php

namespace App\Filters\Frontend\Filters\Order;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class TypeFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param $value
     * @return Builder|null
     */
    public function filter(Builder $builder, $value): ?Builder
    {
        if (in_array($value, ['current', 'previous'])) {
            if ($value === 'current') {
                return $builder->wherein('status', [OrderStatusEnum::PENDING, OrderStatusEnum::ACCEPTED]);
            }elseif ($value === 'previous') {
                return $builder->whereIn('status', [OrderStatusEnum::CANCELLED, OrderStatusEnum::REJECTED, OrderStatusEnum::COMPLETED]);
            }
        }
        return null;
    }
}
