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
            // Since we no longer use parent/child structure, just filter by the category ID
            return $builder->where('category_id', $category->id);
        }
        return $builder;
    }
}
