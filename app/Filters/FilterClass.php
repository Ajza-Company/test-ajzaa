<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class FilterClass
{
    protected Request $request;

    protected array $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Filter Function
     *
     * @param Builder $builder
     * @return Builder
     */
    public function filter(Builder $builder): Builder
    {
        foreach ($this->getFilters() as $filter => $value) {
            $this->resolveFilter($filter)->filter($builder, $value);
        }
        return $builder;
    }

    /**
     * Get Filters Function
     *
     * @return array
     */
    protected function getFilters(): array
    {
        // Use array_filter with a custom callback to preserve "0" values
        return array_filter(
            $this->request->only(array_keys($this->filters)),
            function ($value) {
                return $value !== null; // Preserve 0, remove null
            }
        );
    }

    /**
     * Resolve Filters Function
     *
     * @param $filter
     * @return mixed
     */
    protected function resolveFilter($filter): mixed
    {
        return new $this->filters[$filter];
    }
}
