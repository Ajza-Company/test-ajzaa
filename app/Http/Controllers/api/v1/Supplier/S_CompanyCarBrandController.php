<?php

namespace App\Http\Controllers\api\v1\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\CarBrand\F_CarBrandResource;
use App\Models\CarBrand;

class S_CompanyCarBrandController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $company = userCompany();

        if (is_null($company->car_brand_id)) {
            return response()->json(['message' => 'Company has no car brands'], 404);
        }

        $brandIds = json_decode($company->car_brand_id, true);

        return  F_CarBrandResource::collection(CarBrand::whereIn('id', $brandIds)->get());

    }
}
