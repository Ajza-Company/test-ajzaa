<?php

namespace App\Filters\Supplier;

use App\Filters\FilterClass;
use App\Filters\Supplier\Filters\Store\CategoryFilter;

class StoreFilter extends FilterClass
{
    protected array $filters = [
        'category' => CategoryFilter::class,
    ];
}
