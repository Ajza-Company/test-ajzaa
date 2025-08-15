<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\CarType\F_CarTypeResource;
use App\Models\CarType;
use App\Models\User;
use App\Repositories\Frontend\CarType\Fetch\F_FetchCarTypeInterface;
use Illuminate\Http\Request;

class F_CarTypeController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchCarTypeInterface $fetchCarType
     */
    public function __construct(private F_FetchCarTypeInterface $fetchCarType)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return F_CarTypeResource::collection($this->fetchCarType->fetch(paginate: false));
    }
}
