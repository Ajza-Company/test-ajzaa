<?php

namespace App\Repositories\Frontend\Store\Fetch;

use App\Models\Store;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchStoreRepository extends F_FetchRepository implements F_FetchStoreInterface
{
    /**
     * Create a new instance.
     *
     * @param Store $model
     */
    public function __construct(Store $model)
    {
        parent::__construct($model);
    }
}
