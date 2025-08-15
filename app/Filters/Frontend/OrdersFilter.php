<?php

namespace App\Filters\Frontend;

use App\Filters\FilterClass;
use App\Filters\Frontend\Filters\Order\TypeFilter;

class OrdersFilter extends FilterClass
{
    protected array $filters = [
        'type' => TypeFilter::class
    ];
}
