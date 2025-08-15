<?php

namespace App\Repositories\Supplier\Store\Create;

use App\Models\Store;
use App\Repositories\Frontend\F_CreatingRepository;

class S_CreateStoreRepository extends F_CreatingRepository implements S_CreateStoreInterface
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
