<?php

namespace App\Filters\Frontend\Filters\Product;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Database\Eloquent\Builder;

class AjzaOfferFilter
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
        $hasOffers = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        return $hasOffers
            ? $builder->whereHas('offer', function ($query) use ($hasOffers) {
                $query->where('ajza_offer', true);
            })
            : $builder->whereDoesntHave('offer', function ($query) use ($hasOffers) {
                $query->where('ajza_offer', true);
            });
    }
}
