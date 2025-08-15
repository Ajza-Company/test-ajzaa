<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\CarBrand\F_CarBrandResource;
use App\Models\CarBrand;
use App\Repositories\Frontend\CarBrand\Fetch\F_FetchCarBrandInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class F_CarBrandController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchCarBrandInterface $fetchCarBrand
     */
    public function __construct(private F_FetchCarBrandInterface $fetchCarBrand)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return F_CarBrandResource::collection($this->fetchCarBrand->fetch());
    }
}
