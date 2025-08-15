<?php

namespace App\Repositories\Frontend\User\Create;

use App\Models\User;
use App\Repositories\Frontend\F_CreatingRepository;

class F_CreateUserRepository extends F_CreatingRepository implements F_CreateUserInterface
{
    /**
     * Create a new instance.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
