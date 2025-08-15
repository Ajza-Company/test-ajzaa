<?php

namespace App\Repositories\Frontend\CarBrand\Fetch;

use App\Models\CarBrand;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchCarBrandRepository extends F_FetchRepository implements F_FetchCarBrandInterface
{
    /**
     * Create a new instance.
     *
     * @param CarBrand $model
     */
    public function __construct(CarBrand $model)
    {
        parent::__construct($model);
    }
}
