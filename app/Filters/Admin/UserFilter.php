<?php

namespace App\Filters\Admin;

use App\Filters\Admin\Filters\User\GeneralFilter;
use App\Filters\Admin\Filters\User\StatusFilter;
use App\Filters\Admin\Filters\User\ActiveFilter;
use App\Filters\FilterClass;

class UserFilter extends FilterClass
{
    protected array $filters = [
        'search' => GeneralFilter::class,
        'status' => StatusFilter::class,
        'active' => ActiveFilter::class
    ];
}
