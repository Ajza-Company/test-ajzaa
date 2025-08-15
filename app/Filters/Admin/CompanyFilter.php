<?php

namespace App\Filters\Admin;

use App\Filters\Admin\Filters\Company\GeneralFilter;
use App\Filters\FilterClass;

class CompanyFilter extends FilterClass
{
    protected array $filters = [
        'search' => GeneralFilter::class,
        'category' => GeneralFilter::class
    ];
}
