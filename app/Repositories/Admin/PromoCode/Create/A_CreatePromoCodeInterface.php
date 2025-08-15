<?php

namespace App\Repositories\Admin\PromoCode\Create;

interface A_CreatePromoCodeInterface
{
    /**
     * Create new resource
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;
}
