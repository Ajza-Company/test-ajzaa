<?php

namespace App\Filters\Supplier\Filters\Orders;

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
        if (in_array($value, ['current', 'previous', 'new'])) {
            if ($value === 'current') {
                return $builder->whereIn('status', [OrderStatusEnum::ACCEPTED]);
            }elseif ($value === 'previous') {
                return $builder->whereIn('status', [OrderStatusEnum::REJECTED, OrderStatusEnum::CANCELLED, OrderStatusEnum::COMPLETED]);
            }elseif ($value === 'new') {
                return $builder->whereIn('status', [OrderStatusEnum::ACCEPTED]);
            }
        }
        return null;
    }
}
