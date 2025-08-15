<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::SHOW_ALL_PERMISSIONS);
    }
}
