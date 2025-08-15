<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Enums\EncodingMethodsEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\CarModel\F_CarModelResource;
use App\Repositories\Frontend\CarModel\Fetch\F_FetchCarModelInterface;
use Illuminate\Http\Request;

class F_CarModelController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchCarModelInterface $fetchCarModel
     */
    public function __construct(private F_FetchCarModelInterface $fetchCarModel)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $car_brand)
    {
        $car_brand_id = decodeString($car_brand);
        return F_CarModelResource::collection($this->fetchCarModel->fetch(data: ['car_brand_id' => $car_brand_id], paginate: false));
    }
}
