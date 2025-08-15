<?php

namespace App\Repositories\Admin\Store\Find;

use App\Models\Store;

class A_FindStoreRepository implements A_FindStoreInterface
{
    /**
     * Create a new instance.
     *
     * @param Store $model
     */
    public function __construct(private Store $model)
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
