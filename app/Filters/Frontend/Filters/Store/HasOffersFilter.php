<?php

namespace App\Filters\Frontend\Filters\Store;

use Illuminate\Database\Eloquent\Builder;

class HasOffersFilter
{
    /**
     * Filter Function
     *
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    public function filter(Builder $query, mixed $value): Builder
    {
        $hasOffers = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        return $query->whereHas('storeProducts', function (Builder $productsQuery) use ($hasOffers): void {
            if ($hasOffers) {
                $productsQuery->whereHas('offer', function (Builder $offerQuery): void {
                    $offerQuery->where('ajza_offer', false);
                });
            } else {
                $productsQuery->whereDoesntHave('offer', function (Builder $offerQuery): void {
                    $offerQuery->where('ajza_offer', false);
                });
            }
        });
    }
}
