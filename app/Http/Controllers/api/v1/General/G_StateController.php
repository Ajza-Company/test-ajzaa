<?php

namespace App\Http\Controllers\api\v1\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\State\F_StateResource;
use App\Repositories\Frontend\State\Fetch\F_FetchStateInterface;
use Illuminate\Http\Request;

class G_StateController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchStateInterface $fetchState
     */
    public function __construct(private F_FetchStateInterface $fetchState)
    {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return F_StateResource::collection($this->fetchState->fetch(data: ['country_id' => 1]));
    }
}
