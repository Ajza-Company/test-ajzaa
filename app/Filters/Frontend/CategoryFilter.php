<?php

namespace App\Filters\Frontend;

use App\Filters\FilterClass;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends FilterClass
{
    public function filter(Builder $builder): Builder
    {
        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $builder->whereHas('localized', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        if ($this->request->has('parent_id')) {
            $parentId = $this->request->get('parent_id');
            if ($parentId === 'null' || $parentId === null) {
                $builder->whereNull('parent_id');
            } else {
                $builder->where('parent_id', $parentId);
            }
        }

        if ($this->request->has('is_active')) {
            $builder->where('is_active', $this->request->get('is_active'));
        }

        return $builder;
    }
}
