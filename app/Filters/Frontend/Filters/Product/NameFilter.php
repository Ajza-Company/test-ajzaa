<?php

namespace App\Filters\Frontend\Filters\Product;

use Illuminate\Database\Eloquent\Builder;

class NameFilter
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
        return $builder->whereHas('product', function ($query) use ($value) {
            $query->where('part_number', 'LIKE', "%{$value}%")->orWhereHas('localized', function ($query) use ($value) {
                $query->where('name', 'LIKE', "%{$value}%");
            });
        });
    }
}
