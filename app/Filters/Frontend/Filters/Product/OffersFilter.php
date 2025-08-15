<?php

namespace App\Filters\Frontend\Filters\Product;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Database\Eloquent\Builder;

class OffersFilter
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
        $hasOffers = parseBoolean($value);

        return $hasOffers
            ? $builder->whereHas('offer')
            : $builder->whereDoesntHave('offer');
    }
}
