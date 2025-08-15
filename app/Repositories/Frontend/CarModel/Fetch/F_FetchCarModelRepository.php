<?php

namespace App\Repositories\Frontend\CarModel\Fetch;

use App\Models\CarModel;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchCarModelRepository extends F_FetchRepository implements F_FetchCarModelInterface
{
    /**
     * Create a new instance.
     *
     * @param CarModel $model
     */
    public function __construct(CarModel $model)
    {
        parent::__construct($model);
    }
}
