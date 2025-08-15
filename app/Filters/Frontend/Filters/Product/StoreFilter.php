<?php

namespace App\Filters\Frontend\Filters\Product;

use Illuminate\Database\Eloquent\Builder;

class StoreFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function filter(Builder $builder, $value): Builder
    {
        return $builder->where('store_id', decodeString($value));
    }
}
