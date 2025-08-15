<?php

namespace App\Repositories\Admin\PromoCode\Create;

use App\Models\PromoCode;
use App\Repositories\Frontend\F_CreatingRepository;

class A_CreatePromoCodeRepository extends F_CreatingRepository implements A_CreatePromoCodeInterface
{
    /**
     * Create a new instance.
     *
     * @param PromoCode $model
     */
    public function __construct(PromoCode $model)
    {
        parent::__construct($model);
    }
}
