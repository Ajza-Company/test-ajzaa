<?php

namespace App\Filters\Supplier\Filters\Orders;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter
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
        return $builder->where('id', decodeString($value));
    }
}
