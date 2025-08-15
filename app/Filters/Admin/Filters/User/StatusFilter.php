<?php

namespace App\Filters\Admin\Filters\User;

use Illuminate\Database\Eloquent\Builder;

class StatusFilter
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
        $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        return $builder->where('is_active', !$isActive);
    }
}
