<?php

namespace App\Filters\General;

use App\Filters\FilterClass;
use App\Filters\General\Filters\SupportChatStatusFilter;
use App\Filters\General\Filters\SupportChatUserRoleFilter;

class SupportChatsFilter extends FilterClass
{
    protected array $filters = [
        'status' => SupportChatStatusFilter::class,
        'role' => SupportChatUserRoleFilter::class,
    ];
}