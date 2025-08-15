<?php

namespace App\Repositories\Frontend\CarType\Fetch;

use App\Models\CarType;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchCarTypeRepository extends F_FetchRepository implements F_FetchCarTypeInterface
{
    /**
     * Create a new instance.
     *
     * @param CarType $model
     */
    public function __construct(CarType $model)
    {
        parent::__construct($model);
    }
}
