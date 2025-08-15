<?php

namespace App\Filters\Supplier\Filters\Orders;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
class OrderCompanyFilter
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
        $stores = Company::where('id', decodeString($value))->first()->stores()->pluck('id')->toArray();

        return $builder->whereIn('store_id', $stores);
    }
}
