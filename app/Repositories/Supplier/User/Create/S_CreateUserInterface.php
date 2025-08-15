<?php

namespace App\Repositories\Supplier\User\Create;

interface S_CreateUserInterface
{
    /**
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
