<?php

namespace App\Http\Controllers\api\v1\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Area\F_AreaResource;
use App\Repositories\Frontend\Area\Fetch\F_FetchAreaInterface;

class G_AreaController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchAreaInterface $fetchArea
     */
    public function __construct(private F_FetchAreaInterface $fetchArea)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $city_id)
    {
        return F_AreaResource::collection($this->fetchArea->fetch(data: ['state_id' => decodeString($city_id)]));
    }
}
