<?php

namespace App\Repositories\Admin\Product\Find;

use App\Models\Product;

class A_FindProductRepository implements A_FindProductInterface
{
    /**
     * Create a new instance.
     *
     * @param Product $model
     */
    public function __construct(private Product $model)
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
