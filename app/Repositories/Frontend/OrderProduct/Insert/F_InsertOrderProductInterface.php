<?php

namespace App\Repositories\Frontend\OrderProduct\Insert;

interface F_InsertOrderProductInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data): mixed;
}
