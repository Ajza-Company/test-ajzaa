<?php

namespace App\Repositories\Frontend\Product\Find;

interface F_FindProductInterface
{
    /**
     *
     * @param int $id
     * @param array $with
     * @return mixed
     */
    public function find(int $id, array $with = []): mixed;
}
