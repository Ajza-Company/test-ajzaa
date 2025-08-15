<?php

namespace App\Filters\Admin\Filters\Company;

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
        return $builder->whereHas('localized', function ($query) use ($value) {
            $query->where('name', 'LIKE', "%{$value}%");
        });
    }
}
