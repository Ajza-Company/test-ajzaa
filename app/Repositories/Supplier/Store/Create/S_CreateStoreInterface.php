<?php

namespace App\Repositories\Supplier\Store\Create;

interface S_CreateStoreInterface
{
    /**
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
