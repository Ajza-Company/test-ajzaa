<?php

namespace App\Repositories\Supplier\Order\Find;

use App\Models\Order;

class S_FindOrderRepository implements S_FindOrderInterface
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
