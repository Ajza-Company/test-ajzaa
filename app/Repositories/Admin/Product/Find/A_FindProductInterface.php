<?php

namespace App\Repositories\Admin\Product\Find;

interface A_FindProductInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
