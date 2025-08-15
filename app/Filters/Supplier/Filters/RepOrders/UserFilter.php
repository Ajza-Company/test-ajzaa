<?php

namespace App\Filters\Supplier\Filters\RepOrders;

use App\Enums\OrderStatusEnum;
use App\Enums\RepOrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class UserFilter
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
        return $builder->where('rep_id', decodeString($value));
    }
}
