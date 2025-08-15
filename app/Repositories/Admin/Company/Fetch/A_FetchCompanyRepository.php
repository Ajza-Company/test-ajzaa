<?php

namespace App\Repositories\Admin\Company\Fetch;

use App\Models\Company;
use App\Repositories\Frontend\F_FetchRepository;

class A_FetchCompanyRepository extends F_FetchRepository implements A_FetchCompanyInterface
{
    /**
     * Create a new instance.
     *
     * @param Company $model
     */
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }
}
