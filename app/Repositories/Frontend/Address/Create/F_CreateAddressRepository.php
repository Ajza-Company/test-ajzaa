<?php

namespace App\Repositories\Frontend\Address\Create;

use App\Models\Address;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateAddressRepository extends F_CreatingRepository implements F_CreateAddressInterface
{
    /**
     * Create a new instance.
     *
     * @param Address $model
     */
    public function __construct(Address $model)
    {
        parent::__construct($model);
    }
}
