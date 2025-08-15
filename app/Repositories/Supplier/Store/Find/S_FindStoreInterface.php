<?php

namespace App\Repositories\Supplier\Store\Find;

interface S_FindStoreInterface
{
    /**
     * Create new resource
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
