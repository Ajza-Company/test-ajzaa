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
            $query->where(function($subQuery) use ($value) {
                $subQuery->where('part_number', 'LIKE', "%{$value}%")
                        ->orWhereHas('localized', function ($localizedQuery) use ($value) {
                            $localizedQuery->where('name', 'LIKE', "%{$value}%");
                        });
            });
        });
    }
}
