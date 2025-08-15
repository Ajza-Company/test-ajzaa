<?php

namespace App\Repositories\Admin\Company\Create;

interface F_CreateCompanyInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
