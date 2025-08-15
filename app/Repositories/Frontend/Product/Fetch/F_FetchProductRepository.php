<?php

namespace App\Repositories\Frontend\Product\Fetch;

use App\Models\Product;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchProductRepository extends F_FetchRepository implements F_FetchProductInterface
{
    /**
     * Create a new instance.
     *
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }
}
