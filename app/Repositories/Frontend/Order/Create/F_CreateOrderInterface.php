<?php

namespace App\Repositories\Frontend\Order\Create;

interface F_CreateOrderInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
