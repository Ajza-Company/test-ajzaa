<?php

namespace App\Repositories\Supplier\StoreHour\Insert;

interface S_InsertStoreHourInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data): mixed;
}
