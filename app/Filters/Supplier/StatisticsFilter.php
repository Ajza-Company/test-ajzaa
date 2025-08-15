<?php

namespace App\Filters\Supplier;

use App\Filters\FilterClass;
use App\Filters\Supplier\Filters\Orders\OrderCompanyFilter;
use App\Filters\Supplier\Filters\Orders\OrderStoreFilter;
use App\Filters\Supplier\Filters\Statistics\DateFilter;

class StatisticsFilter extends FilterClass
{
    protected array $filters = [
        'date' => DateFilter::class,
        'company' => OrderCompanyFilter::class,
        'store' =>  OrderStoreFilter::class
    ];
}
