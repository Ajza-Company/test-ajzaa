<?php

namespace App\Filters\General\Filters;

use App\Enums\RepOrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class SupportChatStatusFilter
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
        if (in_array($value, ['open', 'closed', 'pending'])) {
            if ($value == 'open') {
                return $builder->where('status', 'open');
            }elseif ($value === 'closed') {
                return $builder->where('status', 'closed');
            }elseif ($value === 'pending') {
                return $builder->where('status', 'pending');
            }
        }
        return null;
    }
}
