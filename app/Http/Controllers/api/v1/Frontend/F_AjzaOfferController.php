<?php

namespace App\Http\Controllers\api\v1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\AjzaOffer\F_AjzaOfferResource;
use App\Repositories\Frontend\AjzaOffer\Fetch\F_FetchAjzaOfferInterface;
use Illuminate\Http\Request;

class F_AjzaOfferController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchAjzaOfferInterface $fetchAjzaOffer
     */
    public function __construct(private F_FetchAjzaOfferInterface $fetchAjzaOffer)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return F_AjzaOfferResource::collection($this->fetchAjzaOffer->fetch(with: ['store']));
    }
}
