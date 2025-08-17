<?php

namespace App\Repositories\Admin\CarBrand\Find;

use App\Models\CarBrand;

interface A_FindCarBrandInterface
{
    /**
     * Find car brand by ID
     */
    public function find(int $id): CarBrand;
}
