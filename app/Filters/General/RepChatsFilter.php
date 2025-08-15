<?php

namespace App\Filters\General;

use App\Filters\FilterClass;
use App\Filters\General\Filters\StatusFilter;
use App\Filters\Supplier\Filters\Orders\TypeFilter;

class RepChatsFilter extends FilterClass
{
    protected array $filters = [
        'status' => StatusFilter::class
    ];
}
