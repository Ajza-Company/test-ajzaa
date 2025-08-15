<?php

namespace App\Repositories\Frontend\OtpCode\Create;

interface F_CreateOtpCodeInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
