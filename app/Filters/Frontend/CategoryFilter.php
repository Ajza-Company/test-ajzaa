<?php

namespace App\Filters\Frontend;

use App\Filters\FilterClass;
use App\Filters\Frontend\Filters\Category\ParentFilter;
use App\Filters\Frontend\Filters\Category\WithStoreFilter;

class CategoryFilter extends FilterClass
{
    protected array $filters = [
        'with-stores' => WithStoreFilter::class,
        'parent-id' => ParentFilter::class
    ];
}
