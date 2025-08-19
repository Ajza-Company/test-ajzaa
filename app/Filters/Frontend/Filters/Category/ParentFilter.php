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
        // Since we no longer use parent/child structure, this filter is deprecated
        // Return all categories regardless of parent_id
        return $builder;
    }
}
