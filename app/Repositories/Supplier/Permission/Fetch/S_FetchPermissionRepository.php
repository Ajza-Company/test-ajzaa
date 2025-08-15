<?php

namespace App\Repositories\Supplier\Permission\Fetch;

use Spatie\Permission\Models\Permission;

class S_FetchPermissionRepository implements S_FetchPermissionInterface
{
    /**
     * class constructor.
     *
     * @return void
     */
    public function __construct(private Permission $model)
    {
        // ...
    }

    /**
     * @inheritDoc
     */
    public function fetch(): mixed
    {
        return $this->model->select(['id', 'name', 'group_name', 'friendly_name'])->get();
    }
}
