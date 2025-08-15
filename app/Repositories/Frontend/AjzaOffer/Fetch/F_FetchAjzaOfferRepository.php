<?php

namespace App\Repositories\Frontend\AjzaOffer\Fetch;

use App\Models\AjzaOffer;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchAjzaOfferRepository extends F_FetchRepository implements F_FetchAjzaOfferInterface
{
    /**
     * Create a new instance.
     *
     * @param AjzaOffer $model
     */
    public function __construct(AjzaOffer $model)
    {
        parent::__construct($model);
    }
}
