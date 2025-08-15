<?php

namespace App\Repositories\Frontend\Category\Fetch;

use App\Models\Category;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchCategoryRepository extends F_FetchRepository implements F_FetchCategoryInterface
{
    /**
     * Create a new instance.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
