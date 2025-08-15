<?php

namespace App\Repositories\Supplier\RepOrder\Find;

interface S_FindRepOrderInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
