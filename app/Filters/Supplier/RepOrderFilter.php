<?php

namespace App\Filters\Supplier;

use App\Filters\FilterClass;
use App\Filters\Supplier\Filters\RepOrders\StatusFilter;
use App\Filters\Supplier\Filters\RepOrders\UserFilter;

class RepOrderFilter extends FilterClass
{
    protected array $filters = [
        'status' => StatusFilter::class,
        'user' => UserFilter::class
    ];
}
