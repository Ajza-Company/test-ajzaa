<?php

namespace App\Filters\Frontend\Filters\Store;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Database\Eloquent\Builder;

class CityFilter
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
        $state_id = decodeString($value);
        return $builder->whereHas('area', function ($query) use ($state_id) {
            $query->where('state_id', $state_id);
        });
    }
}
