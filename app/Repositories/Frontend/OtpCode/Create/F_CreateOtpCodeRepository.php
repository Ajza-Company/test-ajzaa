<?php

namespace App\Repositories\Frontend\OtpCode\Create;

use App\Models\OtpCode;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateOtpCodeRepository extends F_CreatingRepository implements F_CreateOtpCodeInterface
{
    /**
     * Create a new instance.
     *
     * @param OtpCode $model
     */
    public function __construct(OtpCode $model)
    {
        parent::__construct($model);
    }
}
