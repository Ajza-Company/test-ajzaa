<?php

namespace App\Repositories\Frontend\RepOrder\Create;

use App\Models\RepOrder;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateRepOrderRepository extends F_CreatingRepository implements F_CreateRepOrderInterface
{
    /**
     * Create a new instance.
     *
     * @param RepOrder $model
     */
    public function __construct(RepOrder $model)
    {
        parent::__construct($model);
    }
}
