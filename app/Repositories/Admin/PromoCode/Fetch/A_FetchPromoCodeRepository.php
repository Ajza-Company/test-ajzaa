<?php

namespace App\Repositories\Admin\PromoCode\Fetch;

use App\Models\PromoCode;
use App\Repositories\Frontend\F_FetchRepository;

class A_FetchPromoCodeRepository extends F_FetchRepository implements A_FetchPromoCodeInterface
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
