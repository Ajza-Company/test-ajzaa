<?php

namespace App\Filters\Admin;

use App\Filters\FilterClass;
use App\Filters\Admin\Filters\Store\CategoryFilter;

class StoreFilter extends FilterClass
{
    protected array $filters = [
        'category' => CategoryFilter::class,
    ];
}
