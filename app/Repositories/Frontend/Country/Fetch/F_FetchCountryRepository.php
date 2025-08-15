<?php

namespace App\Repositories\Frontend\Country\Fetch;

use App\Models\Country;
use App\Repositories\Frontend\F_FetchRepository;

class F_FetchCountryRepository extends F_FetchRepository implements F_FetchCountryInterface
{
    /**
     * Create a new instance.
     *
     * @param Country $model
     */
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }
}
