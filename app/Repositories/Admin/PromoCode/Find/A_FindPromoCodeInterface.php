<?php

namespace App\Repositories\Admin\PromoCode\Find;

interface A_FindPromoCodeInterface
{
    /**
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id): mixed;
}
