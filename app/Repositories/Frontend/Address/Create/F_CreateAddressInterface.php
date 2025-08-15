<?php

namespace App\Repositories\Frontend\Address\Create;

interface F_CreateAddressInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
