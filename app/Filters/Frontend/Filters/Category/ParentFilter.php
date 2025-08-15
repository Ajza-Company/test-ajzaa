<?php

namespace App\Filters\Frontend\Filters\Category;

use Illuminate\Database\Eloquent\Builder;

class ParentFilter
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
        if ($value === 'null') {
            return $builder->whereNull('parent_id');
        }
        return $builder->where('parent_id', decodeString($value));
    }
}
