<?php

namespace App\Repositories\Frontend\Product\Find;

use App\Models\Product;

class F_FindProductRepository implements F_FindProductInterface
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
    public function find(int $id, array $with = []): mixed
    {
        return $this->model->with($with)->findOrFail($id);
    }
}
