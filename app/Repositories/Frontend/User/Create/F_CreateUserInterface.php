<?php

namespace App\Repositories\Frontend\User\Create;

interface F_CreateUserInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
