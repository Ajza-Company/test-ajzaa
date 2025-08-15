<?php

namespace App\Repositories\Supplier\Offer\Find;

interface S_FindOfferInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
