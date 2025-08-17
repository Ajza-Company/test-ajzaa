<?php

namespace App\Filters\Frontend;

use App\Filters\Admin\Filters\User\StatusFilter;
use App\Filters\FilterClass;
use App\Filters\Frontend\Filters\Store\CategoryFilter;
use App\Filters\Frontend\Filters\Store\HasOffersFilter;
use App\Filters\Frontend\Filters\Store\NameFilter;
use App\Filters\Frontend\Filters\Store\CityFilter;

class StoreFilter extends FilterClass
{
    protected array $filters = [
        'name' => NameFilter::class,
        'city' => CityFilter::class,
        'category' => CategoryFilter::class,
        'has-offers' => HasOffersFilter::class,
        'status' => StatusFilter::class
    ];
}
