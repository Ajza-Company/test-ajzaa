<?php

namespace App\Filters\General;

use App\Filters\FilterClass;
use App\Filters\Frontend\Filters\Product\CarBrandFilter;
use App\Filters\Frontend\Filters\Product\CarModelFilter;
use App\Filters\General\Filters\Product\CategoryFilter;
use App\Filters\Frontend\Filters\Product\YearFilter;
use App\Filters\General\Filters\Product\NameFilter;
use App\Filters\General\Filters\Product\StoreProductOutOfQuantityFilter;
use App\Filters\General\Filters\Product\StoreProductQuantityFilter;
class ProductFilter extends FilterClass
{
    protected array $filters = [
        'name' => NameFilter::class,
        'category' => CategoryFilter::class,
        'has_stock' => StoreProductQuantityFilter::class,
        'out_of_stock' => StoreProductOutOfQuantityFilter::class,
        'car_brand' => CarBrandFilter::class,
        'car_model' => CarModelFilter::class,
        'year' => YearFilter::class
    ];
}
