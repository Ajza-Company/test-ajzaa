<?php

namespace App\Filters\Admin;

use App\Filters\Admin\Filters\User\ActiveFilter;
use App\Filters\Admin\Filters\User\RoleUsersFilter;
use App\Filters\Admin\Filters\User\UsersIdFilter;
use App\Filters\FilterClass;

class GetUserFilter extends FilterClass
{
    protected array $filters = [
//        'users' => UsersIdFilter::class,
        'role' => RoleUsersFilter::class
    ];
}
