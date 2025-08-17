<?php

namespace App\Repositories\Admin\CarBrand\Find;

use App\Models\CarBrand;

class A_FindCarBrandRepository implements A_FindCarBrandInterface
{
    /**
     * Find car brand by ID
     */
    public function find(int $id): CarBrand
    {
        return CarBrand::with(['locales.locale', 'carModels'])->findOrFail($id);
    }
}
