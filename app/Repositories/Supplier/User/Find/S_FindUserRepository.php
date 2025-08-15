<?php

namespace App\Repositories\Supplier\User\Find;

use App\Models\User;

class S_FindUserRepository implements S_FindUserInterface
{
    /**
     * Create a new instance.
     *
     * @param User $model
     */
    public function __construct(private User $model)
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
