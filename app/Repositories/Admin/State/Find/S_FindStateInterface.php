<?php

namespace App\Repositories\Admin\State\Find;

interface S_FindStateInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
