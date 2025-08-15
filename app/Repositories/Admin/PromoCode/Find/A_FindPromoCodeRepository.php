<?php

namespace App\Repositories\Admin\PromoCode\Find;

use App\Models\PromoCode;

class A_FindPromoCodeRepository implements A_FindPromoCodeInterface
{
    /**
     * Create a new instance.
     *
     * @param PromoCode $model
     */
    public function __construct(private PromoCode $model)
    {
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }
}
