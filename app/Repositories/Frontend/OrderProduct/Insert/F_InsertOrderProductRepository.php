<?php

namespace App\Repositories\Frontend\OrderProduct\Insert;

use App\Models\OrderProduct;

class F_InsertOrderProductRepository implements F_InsertOrderProductInterface
{
    /**
     * Create a new instance.
     *
     * @param OrderProduct $model
     */
    public function __construct(private OrderProduct $model)
    {
    }

    /**
     */
    public function insert(array $data): mixed
    {
        return $this->model->insert($data);
    }
}
