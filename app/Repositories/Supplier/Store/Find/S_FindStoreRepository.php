<?php

namespace App\Repositories\Supplier\Store\Find;

use App\Models\Store;

class S_FindStoreRepository implements S_FindStoreInterface
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
