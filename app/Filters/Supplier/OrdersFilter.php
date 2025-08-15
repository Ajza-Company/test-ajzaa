<?php

namespace App\Filters\Supplier;

use App\Filters\FilterClass;
use App\Filters\Supplier\Filters\Orders\SearchFilter;
use App\Filters\Supplier\Filters\Orders\TypeFilter;

class OrdersFilter extends FilterClass
{
    protected array $filters = [
        'type' => TypeFilter::class,
        'search' => SearchFilter::class
    ];
}
