<?php

namespace App\Repositories\Supplier\Offer\Find;

use App\Models\StoreProductOffer;

class S_FindOfferRepository implements S_FindOfferInterface
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
     * @inheritDoc
     */
    public function find(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }
}
