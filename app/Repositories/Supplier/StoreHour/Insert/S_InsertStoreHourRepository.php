<?php

namespace App\Repositories\Supplier\StoreHour\Insert;

use App\Models\StoreHour;

class S_InsertStoreHourRepository implements S_InsertStoreHourInterface
{
    /**
     * Create a new instance.
     *
     * @param StoreHour $model
     */
    public function __construct(private StoreHour $model)
    {
    }

    /**
     */
    public function insert(array $data): mixed
    {
        return $this->model->insert($data);
    }
}
