<?php

namespace App\Repositories\Admin\RepSales\Fetch;

use App\Models\User;
use App\Repositories\Frontend\F_FetchRepository;

class A_FetchRepSalesRepository extends F_FetchRepository implements A_FetchRepSalesInterface
{
    /**
     * Create a new instance.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
