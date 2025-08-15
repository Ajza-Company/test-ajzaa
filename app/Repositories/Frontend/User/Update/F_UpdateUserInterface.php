<?php

namespace App\Repositories\Frontend\User\Update;

interface F_UpdateUserInterface
{
    /**
     * update new resource
     *
     * @param array $data
     * @return mixed
     */
    public function update(array $data): mixed;
}
