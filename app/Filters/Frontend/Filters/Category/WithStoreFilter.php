<?php

namespace App\Filters\Frontend\Filters\Category;

use Illuminate\Database\Eloquent\Builder;

class WithStoreFilter
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

        // Convert string to boolean
        $isTrue = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        if ($isTrue) {
            if (request()->has('stores-count-limit') && request('stores-count-limit') > 0) {
                $builder->whereHas('stores')->with(['stores' => function ($query) {
                    $query->whereHas('company', fn ($query) => $query->where('is_active', true))->limit(request('stores-count-limit'));
                }]);
            }
        }

        return $builder;
    }
}
