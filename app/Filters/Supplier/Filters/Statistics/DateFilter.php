<?php

namespace App\Filters\Supplier\Filters\Statistics;

use App\Enums\EncodingMethodsEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DateFilter
{
    /**
     * Filter Function
     *
     * @param Builder $builder
     * @param $value
     * @return Builder
     */
    public function filter(Builder $builder, $value): Builder
    {
        // First, decode the URL-encoded value
        $decodedValue = urldecode($value);

        // Split the comma-separated date string into start_date and end_date
        $dates = explode(',', $decodedValue);

        if (count($dates) === 2) {
            [$startDate, $endDate] = $dates;

            // Make sure to handle the end date properly by setting it to the end of the day
            $endDate = Carbon::parse($endDate)->endOfDay();

            // Apply the date range filter
            return $builder->whereBetween('created_at', [
                $startDate,
                $endDate
            ]);
        }

        // Fallback to single date filtering
        return $builder->whereDate('created_at', $decodedValue);
    }
}
