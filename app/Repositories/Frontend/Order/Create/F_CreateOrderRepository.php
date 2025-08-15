<?php

namespace App\Repositories\Frontend\Order\Create;

use App\Models\Order;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateOrderRepository extends F_CreatingRepository implements F_CreateOrderInterface
{
    /**
     * Create a new instance.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
