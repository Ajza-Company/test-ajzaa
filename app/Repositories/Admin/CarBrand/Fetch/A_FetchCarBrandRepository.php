<?php

namespace App\Repositories\Admin\CarBrand\Fetch;

use App\Models\CarBrand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class A_FetchCarBrandRepository implements A_FetchCarBrandInterface
{
    /**
     * Fetch car brands with optional pagination and relationships
     */
    public function fetch(
        bool $paginate = true,
        array $with = [],
        array $withCount = [],
        array $filters = []
    ): LengthAwarePaginator|Collection {
        $query = CarBrand::query();

        // Apply filters
        if (isset($filters['search'])) {
            $query->whereHas('locales', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Apply relationships
        if (!empty($with)) {
            $query->with($with);
        }

        // Always load locales with locale data for proper name display
        $query->with(['locales.locale']);

        // Apply relationship counts
        if (!empty($withCount)) {
            $query->withCount($withCount);
        }

        // Order by
        $query->orderBy('created_at', 'desc');

        // Return paginated or collection
        if ($paginate) {
            return $query->paginate(request()->get('per_page', 15));
        }

        return $query->get();
    }
}
