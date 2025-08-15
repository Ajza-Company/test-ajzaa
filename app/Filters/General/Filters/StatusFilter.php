<?php

namespace App\Filters\General\Filters;

use App\Enums\OrderStatusEnum;
use App\Enums\RepOrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter
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
        if (in_array($value, ['current', 'previous'])) {
            if ($value == 'current') {
                return $builder->whereHas('order', function ($q) {
                    $q->whereIn('status', [RepOrderStatusEnum::PENDING, RepOrderStatusEnum::ACCEPTED]);
                });
            }elseif ($value === 'previous') {
                return $builder->whereHas('order', function ($q) {
                    $q->whereNotIn('status', [RepOrderStatusEnum::PENDING, RepOrderStatusEnum::ACCEPTED]);
                });
            }
        }
        return null;
    }
}
