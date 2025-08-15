<?php

namespace App\Filters\Frontend\Filters\Product;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Database\Eloquent\Builder;

class CarBrandFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder
     */
    public function filter(Builder $builder, mixed $value): Builder
    {
        return $builder->whereRelation('carAttributes', 'car_brand_id', decodeString($value));
    }
}
