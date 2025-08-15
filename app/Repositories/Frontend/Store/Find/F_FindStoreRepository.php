<?php

namespace App\Repositories\Frontend\Store\Find;

use App\Models\Store;

class F_FindStoreRepository implements F_FindStoreInterface
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
        return $this->model->with(['categories' => function ($q) {
            $q->whereHas('localized');
        }])->withCount('products')->findOrFail($id);
    }
}
