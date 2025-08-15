<?php

namespace App\Repositories\Supplier\User\Create;

use App\Models\User;
use App\Repositories\Frontend\F_CreatingRepository;

class S_CreateUserRepository extends F_CreatingRepository implements S_CreateUserInterface
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
