<?php

namespace App\Repositories\Supplier\Offer\Create;

use App\Models\StoreProductOffer;
use App\Repositories\Frontend\F_CreatingRepository;
use App\Repositories\Frontend\F_UpdateOrCreateRepository;

class S_CreateOfferRepository extends F_CreatingRepository implements S_CreateOfferInterface
{
    /**
     * Create a new instance.
     *
     * @param StoreProductOffer $model
     */
    public function __construct(StoreProductOffer $model)
    {
        parent::__construct($model);
    }
}
