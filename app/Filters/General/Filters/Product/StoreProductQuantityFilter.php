<?php

namespace App\Filters\General\Filters\Product;

use Illuminate\Database\Eloquent\Builder;

class StoreProductQuantityFilter
{
    public function filter(Builder $builder): Builder
    {
        return $builder->whereHas('storeProduct', function($query) {
            $query->where('quantity', '!=', 0);
        });
    }
}
