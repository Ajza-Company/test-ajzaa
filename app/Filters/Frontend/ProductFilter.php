<?php

namespace App\Filters\Frontend;

use App\Filters\FilterClass;
use App\Filters\Frontend\Filters\Product\AjzaOfferFilter;
use App\Filters\Frontend\Filters\Product\CarBrandFilter;
use App\Filters\Frontend\Filters\Product\CarModelFilter;
use App\Filters\Frontend\Filters\Product\CarTypeFilter;
use App\Filters\Frontend\Filters\Product\CategoryFilter;
use App\Filters\Frontend\Filters\Product\NameFilter;
use App\Filters\Frontend\Filters\Product\OffersFilter;

class ProductFilter extends FilterClass
{
    protected array $filters = [
        'name' => NameFilter::class,
        'category' => CategoryFilter::class,
        'has-discount' => OffersFilter::class,
        'store' => \App\Filters\Frontend\Filters\Product\StoreFilter::class,
        'car-type' => CarTypeFilter::class,
        'car-brand' => CarBrandFilter::class,
        'car-model' => CarModelFilter::class,
        'has-ajza-offer' => AjzaOfferFilter::class,

    ];
}
