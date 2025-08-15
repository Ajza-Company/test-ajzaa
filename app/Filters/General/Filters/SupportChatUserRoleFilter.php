<?php

namespace App\Filters\General\Filters;

use App\Enums\RepOrderStatusEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Builder;

class SupportChatUserRoleFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param $value
     * @return Builder|null
     */
    public function filter(Builder $builder, $value): ?Builder
    {

            return $builder->whereHas('user', function ($userQuery) use ($value) {
                $userQuery->whereHas('roles', function ($roleQuery) use ($value) {
                    $roleQuery->where('name', 'LIKE', "%{$value}%");
                });
            });

    }
}
