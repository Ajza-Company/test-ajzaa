<?php

namespace App\Repositories\Admin\Product\Fetch;

use App\Models\Product;
use App\Repositories\Frontend\F_FetchRepository;

class A_FetchProductRepository extends F_FetchRepository implements A_FetchProductInterface
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