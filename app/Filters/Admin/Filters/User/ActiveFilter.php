<?php

namespace App\Filters\Admin\Filters\User;

use Illuminate\Database\Eloquent\Builder;

class ActiveFilter
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
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($value) {
            // Return users who have at least one order
            return $builder->has('orders');
        } else {
            // Return users who have no orders
            return $builder->doesntHave('orders');
        }
    }
}
