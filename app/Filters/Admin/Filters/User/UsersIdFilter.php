<?php

namespace App\Filters\Admin\Filters\User;

use Illuminate\Database\Eloquent\Builder;

class UsersIdFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function filter(Builder $builder, array $value): Builder
    {
        return $builder->whereIn('id', array_map('', $value));
    }
}
