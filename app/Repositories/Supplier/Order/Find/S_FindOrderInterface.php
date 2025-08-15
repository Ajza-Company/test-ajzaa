<?php

namespace App\Repositories\Supplier\Order\Find;

interface S_FindOrderInterface
{
    /**
     * Create new resource
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
