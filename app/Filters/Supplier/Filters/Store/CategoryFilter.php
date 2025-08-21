<?php

namespace App\Filters\Supplier\Filters\Store;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter
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
        $categoryId = decodeString($value);
        $category = Category::find($categoryId);
        
        if ($category) {
            return $builder->whereHas('category', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }
        
        return $builder;
    }
}
