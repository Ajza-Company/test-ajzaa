<?php

namespace App\Repositories\Supplier\User\Find;

interface S_FindUserInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
