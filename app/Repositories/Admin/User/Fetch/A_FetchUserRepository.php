<?php

namespace App\Repositories\Admin\User\Fetch;

use App\Models\User;
use App\Repositories\Frontend\F_FetchRepository;

class A_FetchUserRepository extends F_FetchRepository implements A_FetchUserInterface
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
