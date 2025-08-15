<?php

namespace App\Repositories\Frontend\Order\Find;

use App\Models\Order;

class F_FindOrderRepository implements F_FindOrderInterface
{
    /**
     * Create a new instance.
     *
     * @param Order $model
     */
    public function __construct(private Order $model)
    {
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }
}
