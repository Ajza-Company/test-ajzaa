<?php

namespace App\Repositories\Supplier\Offer\Insert;

use App\Models\StoreProductOffer;

class S_InsertOfferRepository implements S_InsertOfferInterface
{
    /**
     * Create a new instance.
     *
     * @param StoreProductOffer $model
     */
    public function __construct(private StoreProductOffer $model)
    {
    }

    /**
     */
    public function insert(array $data): mixed
    {
        return $this->model->insert($data);
    }
}
