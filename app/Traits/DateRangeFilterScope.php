<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

trait DateRangeFilterScope
{
    public function scopeDateRangeFilter(Builder $query, $request): Builder
    {
        if ($request->has('start_date') && $request->start_date) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->has('end_date') && $request->end_date) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query;
    }
}
