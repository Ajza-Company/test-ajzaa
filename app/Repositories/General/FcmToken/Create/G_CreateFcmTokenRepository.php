<?php

namespace App\Repositories\General\FcmToken\Create;

use App\Models\UserFcmToken;
use App\Repositories\Frontend\F_CreatingRepository;

class G_CreateFcmTokenRepository extends F_CreatingRepository implements G_CreateFcmTokenInterface
{
    /**
     *
     * @param UserFcmToken $model
     */
    public function __construct(UserFcmToken $model)
    {
        parent::__construct($model);
    }
}
