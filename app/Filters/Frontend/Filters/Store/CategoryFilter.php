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
            // If it's a parent category, include its children
            $categoryIds = [$category->id];
            if (!$category->parent_id) {
                foreach ($category->children as $child) {
                    $categoryIds[] = $child->id;
                }
            }
            
            return $builder->whereHas('category', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            });
        }
        
        return $builder;
    }
}
