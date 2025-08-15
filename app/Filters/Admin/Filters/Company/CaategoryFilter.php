<?php

namespace App\Filters\Admin\Filters\Company;

use Illuminate\Database\Eloquent\Builder;

class CaategoryFilter
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
        return $builder->where('category_id', decodeString($value));
    }
}
