<?php

namespace App\Repositories\Frontend\Category\Find;

interface F_FindCategoryInterface
{
    /**
     * Create new resource
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
