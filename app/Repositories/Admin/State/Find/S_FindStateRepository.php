<?php

namespace App\Repositories\Admin\State\Find;

use App\Models\State;

class S_FindStateRepository implements S_FindStateInterface
{
    /**
     * Create a new instance.
     *
     * @param State $model
     */
    public function __construct(private State $model)
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
