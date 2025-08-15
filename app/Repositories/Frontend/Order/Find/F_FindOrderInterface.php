<?php

namespace App\Repositories\Frontend\Order\Find;

interface F_FindOrderInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
