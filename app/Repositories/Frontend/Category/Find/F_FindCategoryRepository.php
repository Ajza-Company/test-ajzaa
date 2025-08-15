<?php

namespace App\Repositories\Frontend\Category\Find;

use App\Models\Category;

class F_FindCategoryRepository implements F_FindCategoryInterface
{
    /**
     * Create a new instance.
     *
     * @param Category $model
     */
    public function __construct(private Category $model)
    {
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): mixed
    {
        return $this->model->with(['variants','translations','localized','stores' => function ($q) {
            $q->whereHas('localized');
        }])->findOrFail($id);
    }
}
