<?php

namespace App\Http\Controllers\api\v1\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Frontend\Country\F_CountryResource;
use App\Repositories\Frontend\Country\Fetch\F_FetchCountryInterface;

class G_CountryController extends Controller
{
    /**
     * Create a new instance.
     *
     * @param F_FetchCountryInterface $fetchCountry
     */
    public function __construct(private F_FetchCountryInterface $fetchCountry)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return F_CountryResource::collection($this->fetchCountry->fetch(data: ['is_active'=> true]));
    }
}