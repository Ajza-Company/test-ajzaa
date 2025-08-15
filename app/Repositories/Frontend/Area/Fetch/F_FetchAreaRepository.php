<?php

namespace App\Repositories\Frontend\Area\Fetch;

use App\Models\Area;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchAreaRepository extends F_FetchRepository implements F_FetchAreaInterface
{
    /**
     * Create a new instance.
     *
     * @param Area $model
     */
    public function __construct(Area $model)
    {
        parent::__construct($model);
    }
}
