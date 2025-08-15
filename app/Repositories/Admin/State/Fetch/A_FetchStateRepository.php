<?php

namespace App\Repositories\Admin\State\Fetch;

use App\Models\State;
use App\Repositories\Frontend\F_FetchRepository;

class A_FetchStateRepository extends F_FetchRepository implements A_FetchStateInterface
{
    /**
     * Create a new instance.
     *
     * @param State $model
     */
    public function __construct(State $model)
    {
        parent::__construct($model);
    }
}
