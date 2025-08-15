<?php

namespace App\Repositories\Supplier\RepOrder\Find;

use App\Models\RepOrder;

class S_FindRepOrderRepository implements S_FindRepOrderInterface
{
    /**
     * Create a new instance.
     *
     * @param RepOrder $model
     */
    public function __construct(private RepOrder $model)
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
