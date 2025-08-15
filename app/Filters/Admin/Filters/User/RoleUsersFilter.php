<?php

namespace App\Filters\Admin\Filters\User;

use Illuminate\Database\Eloquent\Builder;

class RoleUsersFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function filter(Builder $builder, string $value): Builder
    {
        return $builder->whereHas('roles', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }
}
