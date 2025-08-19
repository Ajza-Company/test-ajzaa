<?php

namespace App\Filters\Frontend\Filters\Category;

use App\Filters\FilterClass;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends FilterClass
{
    public function filter(Builder $builder): Builder
    {
        if ($this->request->has('search')) {
            $search = $this->request->get('search');
            $builder->whereHas('localized', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        return $builder;
    }
}
