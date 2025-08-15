<?php

namespace App\Repositories\Supplier\Offer\Insert;

interface S_InsertOfferInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data): mixed;
}
