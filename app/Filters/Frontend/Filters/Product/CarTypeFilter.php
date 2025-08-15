<?php

namespace App\Filters\Frontend\Filters\Product;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Database\Eloquent\Builder;

class CarTypeFilter
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
        return $builder->whereRelation('product.carAttributes', 'car_type_id', decodeString($value));
    }
}
