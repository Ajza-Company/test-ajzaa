<?php

namespace App\Filters\Frontend\Filters\Store;

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
            // Simple filter by category ID (flat structure)
            return $builder->whereHas('category', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }
        
        return $builder;
    }
}
