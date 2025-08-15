<?php

namespace App\Repositories\Frontend\StoreReview\Create;

use App\Models\StoreReview;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateStoreReviewRepository extends F_CreatingRepository implements F_CreateStoreReviewInterface
{
    /**
     * Create a new instance.
     *
     * @param StoreReview $model
     */
    public function __construct(StoreReview $model)
    {
        parent::__construct($model);
    }
}
