<?php

namespace App\Repositories\Admin\Store\Find;

interface A_FindStoreInterface
{
    /**
     * Create new resource
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
