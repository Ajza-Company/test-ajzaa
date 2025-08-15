<?php

namespace App\Filters\Supplier\Filters\Orders;

use Illuminate\Database\Eloquent\Builder;
class OrderStoreFilter
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
        return $builder->where('store_id', decodeString($value));
    }
}
