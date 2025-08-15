<?php

namespace App\Repositories\Frontend\Store\Find;

interface F_FindStoreInterface
{
    /**
     * Create new resource
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
