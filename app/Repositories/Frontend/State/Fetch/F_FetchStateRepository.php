<?php

namespace App\Repositories\Frontend\State\Fetch;

use App\Models\State;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchStateRepository extends F_FetchRepository implements F_FetchStateInterface
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
