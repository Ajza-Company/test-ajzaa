<?php

namespace App\Repositories\Admin\CarBrand\Fetch;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface A_FetchCarBrandInterface
{
    /**
     * Fetch car brands with optional pagination and relationships
     */
    public function fetch(
        bool $paginate = true,
        array $with = [],
        array $withCount = [],
        array $filters = []
    ): LengthAwarePaginator|Collection;
}
