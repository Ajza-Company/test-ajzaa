<?php

namespace App\Filters\Admin\Filters\User;

use Illuminate\Database\Eloquent\Builder;

class GeneralFilter
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
        return $builder->where('name', 'LIKE', "%{$value}%")
            ->orWhere('full_mobile', 'LIKE', "%{$value}%")
            ->orWhere('email', 'LIKE', "%{$value}%");
    }
}
