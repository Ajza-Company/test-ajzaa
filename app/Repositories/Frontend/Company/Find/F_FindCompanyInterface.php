<?php

namespace App\Repositories\Frontend\Company\Find;

interface F_FindCompanyInterface
{
    /**
     * Create new resource
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
