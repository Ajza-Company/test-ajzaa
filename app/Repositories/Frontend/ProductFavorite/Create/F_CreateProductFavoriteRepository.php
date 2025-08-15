<?php

namespace App\Repositories\Frontend\ProductFavorite\Create;

use App\Models\ProductFavorite;
use App\Repositories\Frontend\F_FirstOrCreateRepository;

class F_CreateProductFavoriteRepository extends F_FirstOrCreateRepository  implements F_CreateProductFavoriteInterface
{
    /**
     * Create a new instance.
     *
     * @param ProductFavorite $model
     */
    public function __construct(ProductFavorite $model)
    {
        parent::__construct($model);
    }
}
