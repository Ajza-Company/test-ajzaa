<?php

namespace App\Repositories\General\FcmToken\Create;

interface G_CreateFcmTokenInterface
{
    /**
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
