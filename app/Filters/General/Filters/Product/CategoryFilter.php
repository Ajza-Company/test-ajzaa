<?php

namespace App\Filters\General\Filters\Product;

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
            $categories = [$category->id];
            if (!$category->parent_id) {
                foreach ($category->children as $child) {
                    $categories[] = $child->id;
                }
            }
            return $builder->whereIn('category_id', $categories);
        }
        return $builder;
    }
}
