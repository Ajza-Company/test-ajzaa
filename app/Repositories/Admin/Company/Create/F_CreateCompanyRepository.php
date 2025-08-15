<?php

namespace App\Repositories\Admin\Company\Create;

use App\Models\Company;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateCompanyRepository extends F_CreatingRepository implements F_CreateCompanyInterface
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
