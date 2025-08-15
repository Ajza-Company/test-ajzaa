<?php

namespace App\Repositories\Frontend\RepOrder\Create;

interface F_CreateRepOrderInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
