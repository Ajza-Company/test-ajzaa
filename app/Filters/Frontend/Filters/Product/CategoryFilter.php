<?php

namespace App\Filters\Frontend\Filters\Product;

use App\Enums\EncodingMethodsEnum;
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
        $category = Category::find(decodeString($value));
        if ($category) {
            if (!$category->parent_id) {
                $categories = [$category->id];
                if ($category->children && is_array($category->children)) {
                    foreach ($category->children as $child) {
                        $categories[] = $child->id;
                    }
                }
                return $builder->whereHas('product', fn ($query) => $query->whereIn('category_id', $categories));
            }
            return $builder->whereRelation('product', 'category_id', decodeString($value));
        }
        return $builder;
    }
}
